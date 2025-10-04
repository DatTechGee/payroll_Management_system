<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Deduction;
use Carbon\Carbon;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::where('role', 'employee')->get();
        $currentMonth = Carbon::create(2025, 3, 1)->format('Y-m'); // Set to March 2025

        foreach ($employees as $employee) {
            // Create payroll record
            $payroll = new Payroll();
            $payroll->employee_id = $employee->id;
            $payroll->payroll_id = 'PAY-' . date('Ym') . '-' . str_pad($employee->id, 4, '0', STR_PAD_LEFT);
            $payroll->pay_month = $currentMonth;
            $payroll->basic_salary = $employee->basic_salary;
            $payroll->allowance = $employee->allowance;
            $payroll->overtime_hours = 0;
            $payroll->overtime_pay = 0;
            $payroll->bonus = 0;
            
            // Calculate gross pay
            $payroll->gross_pay = $payroll->basic_salary + $payroll->allowance;
            
            // Set other deductions to 0
            $payroll->other_deductions = 0;
            
            // Calculate net pay (no deductions in payroll now)
            $payroll->net_pay = $payroll->gross_pay;
            $payroll->date_processed = now();
            $payroll->save();

            // Create deduction record
            $deduction = new Deduction();
            $deduction->payroll_id = $payroll->id;
            $deduction->tax = $payroll->gross_pay * 0.1; // 10% tax
            $deduction->pension = $payroll->gross_pay * 0.05; // 5% pension
            $deduction->other_deductions = 0;
            $deduction->total_deduction = $deduction->tax + $deduction->pension;
            $deduction->save();
        }
    }
}