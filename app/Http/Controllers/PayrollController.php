<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_month' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate overtime pay (if any)
            $overtime_pay = $request->overtime_hours 
                ? ($request->basic_salary / 176) * $request->overtime_hours * 1.5 
                : 0;

            // Calculate gross pay
            $gross_pay = $request->basic_salary + 
                        ($request->allowance ?? 0) + 
                        $overtime_pay + 
                        ($request->bonus ?? 0);

            // Get or create deductions
            $deduction = Deduction::firstOrCreate(
                ['payroll_id' => null],
                [
                    'tax' => $this->calculateTax($gross_pay),
                    'pension' => $this->calculatePension($request->basic_salary),
                    'other_deductions' => 0,
                    'total_deduction' => 0
                ]
            );

            // Calculate total deductions
            $total_deduction = $deduction->tax + $deduction->pension + $deduction->other_deductions;
            
            // Update total deduction
            $deduction->total_deduction = $total_deduction;
            $deduction->save();

            // Calculate net pay
            $net_pay = $gross_pay - $total_deduction;

            // Create payroll record
            $payroll = Payroll::create([
                'employee_id' => $request->employee_id,
                'pay_month' => $request->pay_month,
                'basic_salary' => $request->basic_salary,
                'allowance' => $request->allowance ?? 0,
                'overtime_hours' => $request->overtime_hours ?? 0,
                'overtime_pay' => $overtime_pay,
                'bonus' => $request->bonus ?? 0,
                'gross_pay' => $gross_pay,
                'tax_deduction' => $deduction->tax,
                'pension_deduction' => $deduction->pension,
                'other_deductions' => $deduction->other_deductions,
                'net_pay' => $net_pay,
                'date_processed' => now(),
            ]);

            // Associate deduction with payroll
            $deduction->payroll_id = $payroll->id;
            $deduction->save();

            DB::commit();

            return redirect()->route('admin.payrolls.show', $payroll)
                           ->with('success', 'Payroll processed successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to process payroll. Please try again.');
        }
    }

    private function calculateTax($gross_pay)
    {
        // Progressive tax calculation
        if ($gross_pay <= 30000) {
            return $gross_pay * 0.07;
        } elseif ($gross_pay <= 60000) {
            return $gross_pay * 0.11;
        } elseif ($gross_pay <= 120000) {
            return $gross_pay * 0.15;
        } elseif ($gross_pay <= 240000) {
            return $gross_pay * 0.19;
        } else {
            return $gross_pay * 0.24;
        }
    }

    private function calculatePension($basic_salary)
    {
        // Standard pension calculation (8% of basic salary)
        return $basic_salary * 0.08;
    }

    // Other controller methods...
}