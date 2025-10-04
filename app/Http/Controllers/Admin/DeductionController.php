<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeductionController extends Controller
{
    public function index(Request $request)
    {
        // Base query with relationships
        $query = Deduction::with(['payroll.employee']);

        // Apply month filter
        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereHas('payroll', function ($q) use ($month) {
                $q->whereYear('pay_month', substr($month, 0, 4))
                    ->whereMonth('pay_month', substr($month, -2));
            });
        } else {
            // Default to current month
            $currentMonth = now();
            $query->whereHas('payroll', function ($q) use ($currentMonth) {
                $q->whereYear('pay_month', $currentMonth->year)
                    ->whereMonth('pay_month', $currentMonth->month);
            });
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $query->whereHas('payroll.employee', function ($q) use ($request) {
                $q->whereHas('department', function ($query) use ($request) {
                    $query->where('name', $request->department_id);
                });
            });
        }

        // Get the filtered deductions
        $deductions = $query->latest()->paginate(10);

        // Calculate summary statistics
        $statsQuery = clone $query;
        $totalDeductions = $statsQuery->sum('total_deduction');

        // Create new query for employee count to avoid join conflicts
        $employeesCount = DB::table('deductions')
            ->join('payrolls', 'deductions.payroll_id', '=', 'payrolls.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('payrolls')
                    ->whereRaw('deductions.payroll_id = payrolls.id')
                    ->whereYear('pay_month', now()->year)
                    ->whereMonth('pay_month', now()->month);
            })
            ->distinct()
            ->count('payrolls.employee_id');

        // Calculate average tax rate
        $averageTaxRate = DB::table('deductions')
            ->join('payrolls', 'deductions.payroll_id', '=', 'payrolls.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('payrolls')
                    ->whereRaw('deductions.payroll_id = payrolls.id')
                    ->whereYear('pay_month', now()->year)
                    ->whereMonth('pay_month', now()->month);
            })
            ->selectRaw('AVG((deductions.tax / payrolls.gross_pay) * 100) as avg_tax_rate')
            ->value('avg_tax_rate');

        // Get unique departments for filter
        $departments = Employee::distinct('department_id')->pluck('department_id');

        return view('admin.deductions.index', compact(
            'deductions',
            'departments',
            'totalDeductions',
            'employeesCount',
            'averageTaxRate'
        ));
    }

    public function create()
    {
        $employees = Employee::where('role', 'employee')->get();

        return view('admin.deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'deduction_month' => 'required|date_format:Y-m',
            'tax_rate' => 'required|numeric|between:0,100',
            'pension_rate' => 'required|numeric|between:0,100',
            'other_deductions' => 'nullable|numeric|min:0',
            'other_deductions_description' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Get the employee
            $employee = Employee::findOrFail($validated['employee_id']);

            // Lock the employee record for update to prevent race conditions
            $employee = Employee::where('id', $validated['employee_id'])->lockForUpdate()->first();
            if (! $employee) {
                DB::rollback();

                return back()->withInput()->with('error', 'Employee not found.');
            }

            // Get or create payroll for this month
            $payroll = Payroll::where(
                ['employee_id' => $validated['employee_id'],
                    'pay_month' => $validated['deduction_month'].'-01']
            )->first();

            if (! $payroll) {
                $payroll = new Payroll([
                    'employee_id' => $validated['employee_id'],
                    'pay_month' => $validated['deduction_month'].'-01',
                    'basic_salary' => $employee->basic_salary,
                    'allowance' => $employee->allowance,
                    'overtime_hours' => 0,
                    'overtime_pay' => 0,
                    'bonus' => 0,
                    'gross_pay' => $employee->basic_salary + ($employee->allowance ?? 0),
                    'tax_deduction' => 0,
                    'pension_deduction' => 0,
                    'other_deductions' => 0,
                    'net_pay' => $employee->basic_salary + ($employee->allowance ?? 0),
                    'date_processed' => now(),
                ]);
                $payroll->save();
            }

            // Check if deduction already exists for this payroll
            if ($payroll && Deduction::where('payroll_id', $payroll->id)->exists()) {
                DB::rollback();

                return back()->withInput()
                    ->with('error', 'A deduction record already exists for this employee in the selected month.');
            }

            // Create deduction with exact values
            $deduction = new Deduction([
                'payroll_id' => $payroll->id,
                'tax_rate' => $validated['tax_rate'],
                'pension_rate' => $validated['pension_rate'],
                'tax' => round($payroll->gross_pay * ($validated['tax_rate'] / 100), 2),
                'pension' => round($payroll->gross_pay * ($validated['pension_rate'] / 100), 2),
                'other_deductions' => $validated['other_deductions'] ?? 0,
                'other_deductions_description' => $validated['other_deductions_description'],
            ]);

            $deduction->total_deduction = $deduction->tax + $deduction->pension + $deduction->other_deductions;
            $deduction->save();

            // Update payroll with the calculated deductions
            $payroll->tax_deduction = $deduction->tax;
            $payroll->pension_deduction = $deduction->pension;
            $payroll->other_deductions = $deduction->other_deductions;
            $payroll->net_pay = $payroll->gross_pay - $deduction->total_deduction;
            $payroll->save();

            DB::commit();

            return redirect()->route('admin.deductions.index')
                ->with('success', 'Deduction created successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Failed to create deduction. '.$e->getMessage());
        }
    }

    public function edit(Deduction $deduction)
    {
        // Use stored rates or calculate if not available
        $taxRate = $deduction->tax_rate ?? ($deduction->payroll->gross_pay > 0 ?
            round(($deduction->tax / $deduction->payroll->gross_pay) * 100, 2) : 0);
        $pensionRate = $deduction->pension_rate ?? ($deduction->payroll->gross_pay > 0 ?
            round(($deduction->pension / $deduction->payroll->gross_pay) * 100, 2) : 0);

        return view('admin.deductions.edit', compact('deduction', 'taxRate', 'pensionRate'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $validated = $request->validate([
            'tax_rate' => 'required|numeric|between:0,100',
            'pension_rate' => 'required|numeric|between:0,100',
            'other_deductions' => 'nullable|numeric|min:0',
            'other_deductions_description' => 'nullable|string|max:255',
        ], [
            'tax_rate.required' => 'Tax rate is required.',
            'tax_rate.between' => 'Tax rate must be between 0 and 100.',
            'pension_rate.required' => 'Pension rate is required.',
            'pension_rate.between' => 'Pension rate must be between 0 and 100.',
            'other_deductions.min' => 'Other deductions cannot be negative.',
            'other_deductions_description.required_with' => 'Please provide a description for other deductions.',
        ]);

        try {
            DB::beginTransaction();

            // Get the gross pay
            $grossPay = $deduction->payroll->gross_pay;

            // Save the custom rates
            $deduction->tax_rate = $validated['tax_rate'];
            $deduction->pension_rate = $validated['pension_rate'];

            // Calculate and save the amounts
            $deduction->tax = round($grossPay * ($validated['tax_rate'] / 100), 2);
            $deduction->pension = round($grossPay * ($validated['pension_rate'] / 100), 2);
            $deduction->other_deductions = $validated['other_deductions'] ? round($validated['other_deductions'], 2) : 0;
            $deduction->other_deductions_description = $validated['other_deductions'] ? $validated['other_deductions_description'] : null;

            // Calculate total deduction before saving
            $deduction->total_deduction = $deduction->tax + $deduction->pension + $deduction->other_deductions;
            $deduction->save();

            // Update associated payroll
            $payroll = $deduction->payroll;
            if ($payroll) {
                $payroll->tax_deduction = $deduction->tax;
                $payroll->pension_deduction = $deduction->pension;
                $payroll->other_deductions = $deduction->other_deductions;
                $payroll->net_pay = $payroll->gross_pay - $deduction->total_deduction;
                $payroll->save();
            }

            DB::commit();

            return redirect()->route('admin.deductions.index')
                ->with('success', 'Deduction updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Failed to update deduction. '.$e->getMessage());
        }
    }

    public function destroy(Deduction $deduction)
    {
        try {
            DB::beginTransaction();

            // Get associated payroll before deleting deduction
            $payroll = $deduction->payroll;

            // Delete the deduction
            $deduction->delete();

            // Update payroll totals
            if ($payroll) {
                $payroll->tax_deduction = 0;
                $payroll->pension_deduction = 0;
                $payroll->other_deductions = 0;
                $payroll->net_pay = $payroll->gross_pay;
                $payroll->save();
            }

            DB::commit();

            return redirect()->route('admin.deductions.index')
                ->with('success', 'Deduction deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->with('error', 'Failed to delete deduction. '.$e->getMessage());
        }
    }
}
