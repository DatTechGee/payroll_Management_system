<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Payroll::with(['employee', 'deductions' => function($query) {
            $query->latest();
        }]);

        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $baseQuery->whereYear('pay_month', $date->year)
                ->whereMonth('pay_month', $date->month);
        } else {
            $date = Carbon::now();
            $baseQuery->whereYear('pay_month', $date->year)
                ->whereMonth('pay_month', $date->month);
        }

        if ($request->filled('department')) {
            $baseQuery->whereHas('employee', function ($q) use ($request) {
                $q->whereHas('department', function($query) use ($request) {
                    $query->where('name', $request->department);
                });
            });
        }

        // Clone the query for aggregates to avoid pagination issues
        $aggregateQuery = clone $baseQuery;

        $payrolls = $baseQuery->latest()->paginate(10);
        $departments = \App\Models\Department::orderBy('name')->pluck('name');

        // Calculate totals
        $totalPayroll = $aggregateQuery->sum('net_pay');
        $totalDeductions = DB::table('deductions')
            ->whereIn('payroll_id', $aggregateQuery->pluck('id'))
            ->sum('total_deduction');
        $totalBonuses = $aggregateQuery->sum('bonus');
        $totalEmployees = $aggregateQuery->count();

        return view('admin.payroll.index', compact(
            'payrolls',
            'departments',
            'totalPayroll',
            'totalDeductions',
            'totalBonuses',
            'totalEmployees'
        ));
    }

    /**
     * Show the form for processing a new payroll.
     */
    public function process()
    {
        $employees = Employee::where('role', 'employee')->get();

        return view('admin.payroll.process', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_month' => 'required|date_format:Y-m',
            'bonus' => 'nullable|numeric|min:0',
            'bonus_type' => 'nullable|in:fixed,percentage',
            'notes' => 'nullable|string|max:1000',
            'allowance' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|between:0,100',
            'pension_rate' => 'nullable|numeric|between:0,100',
            'other_deductions' => 'nullable|numeric|min:0',
            'deduction_type' => 'nullable|in:fixed,percentage',
            'other_deductions_description' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // Check if payroll already exists for this month
        $exists = Payroll::where('employee_id', $employee->id)
            ->whereYear('pay_month', Carbon::parse($request->pay_month)->year)
            ->whereMonth('pay_month', Carbon::parse($request->pay_month)->month)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Payroll already processed for this employee this month.');
        }

        try {
            DB::beginTransaction();

            // Calculate attendance
            $startDate = Carbon::parse($request->pay_month)->startOfMonth();
            $endDate = Carbon::parse($request->pay_month)->endOfMonth();

            $workingDays = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'present')
                ->count();

            $overtimeHours = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'overtime')
                ->sum('overtime_hours');

            // Calculate basic components
            $basicSalary = floatval($employee->basic_salary);
            $standardAllowance = floatval($employee->allowance ?? 0);
            $additionalAllowance = $request->filled('allowance') ? floatval($request->allowance) : 0;
            $allowance = $standardAllowance + $additionalAllowance;

            // Calculate overtime pay
            $overtimeHours = 0; // Initialize overtime hours
            $overtimePay = 0;   // Initialize overtime pay
            
            // Calculate hourly rate for overtime
            $workingDaysConfig = config('payroll.working_days_per_month', 22);
            $hoursPerDay = config('payroll.working_hours_per_day', 8);
            $overtimeMultiplier = config('payroll.overtime_multiplier', 1.5);
            $precision = config('payroll.decimal_precision', 2);
            
            $hourlyRate = round($basicSalary / ($workingDaysConfig * $hoursPerDay), $precision);
            if ($overtimeHours > 0) {
                $overtimePay = round($overtimeHours * ($hourlyRate * $overtimeMultiplier), $precision);
            }

            // Handle bonus (can be percentage or fixed amount)
            $bonus = 0;
            if ($request->filled('bonus')) {
                $bonusAmount = floatval($request->bonus);
                if ($request->bonus_type === 'percentage') {
                    $bonus = ($bonusAmount / 100) * $basicSalary;
                } else {
                    $bonus = $bonusAmount;
                }
            }

            // Handle other deductions (can be percentage or fixed amount)
            $otherDeductions = 0;
            if ($request->filled('other_deductions')) {
                $deductionAmount = floatval($request->other_deductions);
                if ($request->deduction_type === 'percentage') {
                    $otherDeductions = ($deductionAmount / 100) * $basicSalary;
                } else {
                    $otherDeductions = $deductionAmount;
                }
            }

            // Calculate total overtime pay
            $totalOvertimePay = $overtimePay;
            
            // Calculate gross pay including all components
            $grossPay = $basicSalary + $allowance + $bonus + $totalOvertimePay;

            // Generate unique payroll ID
            $payMonth = Carbon::parse($request->pay_month);
            $randomStr = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);
            $payrollId = 'PAY-'.$payMonth->format('Ym').'-'.str_pad($employee->id, 4, '0', STR_PAD_LEFT).'-'.$randomStr;

            // Calculate overtime pay using configuration values
            $workingDaysConfig = config('payroll.working_days_per_month', 22);
            $hoursPerDay = config('payroll.working_hours_per_day', 8);
            $overtimeMultiplier = config('payroll.overtime_multiplier', 1.5);
            $precision = config('payroll.decimal_precision', 2);

            $hourlyRate = round($basicSalary / ($workingDaysConfig * $hoursPerDay), $precision);
            $overtimePay = round($overtimeHours * ($hourlyRate * $overtimeMultiplier), $precision);

            // Calculate gross pay
            $grossPay = $basicSalary + $allowance + $overtimePay + $bonus;

            // Get other deductions
            $otherDeductions = round(floatval($request->other_deductions ?? 0), $precision);
            $otherDeductionsDesc = $request->other_deductions_description;

            // Generate unique payroll ID if not already set
            if (empty($payrollId)) {
                $payMonth = Carbon::parse($request->pay_month);
                $randomStr = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);
                $payrollId = 'PAY-'.$payMonth->format('Ym').'-'.str_pad($employee->id, 4, '0', STR_PAD_LEFT).'-'.$randomStr;
            }

            // Create payroll record first
            $payroll = Payroll::create([
                'payroll_id' => $payrollId,
                'employee_id' => $employee->id,
                'pay_month' => $startDate,
                'basic_salary' => $basicSalary,
                'allowance' => $allowance,
                'overtime_hours' => $overtimeHours,
                'overtime_pay' => $totalOvertimePay,
                'bonus' => $bonus,
                'bonus_type' => $request->bonus_type,
                'gross_pay' => $grossPay,
                'other_deductions' => $otherDeductions,
                'net_pay' => $grossPay,
                'date_processed' => now(),
                'notes' => $request->notes
            ]);

            // Get rates from request or config
            $taxRate = $request->filled('tax_rate') 
                ? $request->tax_rate / 100 
                : config('payroll.tax_rate', 0.1);
            $pensionRate = $request->filled('pension_rate') 
                ? $request->pension_rate / 100 
                : config('payroll.pension_rate', 0.05);

            // Create deduction record with calculated values
            $deduction = new Deduction([
                'payroll_id' => $payroll->id,
                'tax_rate' => $taxRate * 100, // Convert to percentage
                'pension_rate' => $pensionRate * 100, // Convert to percentage
                'tax' => round($grossPay * $taxRate, 2),
                'pension' => round($grossPay * $pensionRate, 2),
                'other_deductions' => $otherDeductions,
                'other_deductions_description' => $request->other_deductions_description,
                'total_deduction' => 0 // Will be calculated in the saving event
            ]);
            
            $deduction->save(); // This will trigger the saving event that calculates total_deduction

            // Update payroll with final amounts
            $payroll->update([
                'bonus' => $bonus,
                'other_deductions' => $otherDeductions,
                'gross_pay' => $grossPay,
                'net_pay' => $grossPay - $deduction->total_deduction
            ]);

            // Update payroll with deduction amounts
            $payroll->tax_deduction = $deduction->tax;
            $payroll->pension_deduction = $deduction->pension;
            $payroll->other_deductions = $deduction->other_deductions;
            $payroll->net_pay = $grossPay - $deduction->total_deduction;
            $payroll->save();

            // Refresh payroll to get the latest relationships
            $payroll->load(['employee', 'deductions']);

            DB::commit();

            return redirect()->route('admin.payroll.index')
                ->with('success', 'Payroll processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to process payroll: '.$e->getMessage());
        }
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee', 'deductions');

        return view('admin.payroll.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $payroll->load('employee', 'deductions');

        return view('admin.payroll.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'other_deductions_description' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update payroll
            $payroll->allowance = floatval($request->allowance ?? $payroll->allowance);
            $payroll->bonus = floatval($request->bonus ?? 0);
            $payroll->other_deductions = floatval($request->other_deductions ?? 0);

            // Recalculate gross pay
            $payroll->gross_pay = $payroll->basic_salary + $payroll->allowance + $payroll->overtime_pay + $payroll->bonus;

            // Save payroll first to ensure gross_pay is updated
            $payroll->save();

            // Get or create deduction record
            $deduction = $payroll->deductions()->first() ?? new Deduction;

            // Only set the other_deductions and description
            $deduction->fill([
                'payroll_id' => $payroll->id,
                'other_deductions' => floatval($request->other_deductions ?? 0),
                'other_deductions_description' => $request->other_deductions_description,
            ]);

            // Save deduction (this will trigger the boot method to calculate tax and pension)
            if (! $deduction->exists) {
                $payroll->deductions()->save($deduction);
            } else {
                $deduction->save();
            }
            
            // Calculate total deductions
            $deduction->refresh();
            $totalDeductions = $deduction->total_deduction;

            // Update net pay based on the new deductions
            $payroll->net_pay = $payroll->gross_pay - $totalDeductions;
            $payroll->save();

            DB::commit();

            return redirect()->route('admin.payroll.show', $payroll)
                ->with('success', 'Payroll updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update payroll: '.$e->getMessage());
        }

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Payroll updated successfully.');
    }

    /**
     * Delete the payroll record and its associated deductions.
     */
    public function destroy(Payroll $payroll)
    {
        // Delete associated deductions first
        $payroll->deductions()->delete();

        // Delete the payroll record
        $payroll->delete();

        return redirect()->route('admin.payroll.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    /**
     * Generate PDF payslip
     */
    public function generatePayslip(Payroll $payroll)
    {
        $payroll->load(['employee', 'deductions']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.payroll.payslip', compact('payroll'));
        
        // Set paper size and orientation
        $pdf->setPaper('A4');
        
        // Return the PDF for download
        return $pdf->download("payslip-{$payroll->payroll_id}.pdf");
    }
}
