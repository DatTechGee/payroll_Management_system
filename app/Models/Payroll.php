<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'payroll_id',
        'employee_id',
        'pay_month',
        'basic_salary',
        'allowance',
        'overtime_hours',
        'overtime_pay',
        'bonus',
        'gross_pay',
        'other_deductions',
        'net_pay',
        'date_processed',
        'status',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_APPROVED = 'approved';
    const STATUS_PAID = 'paid';

    protected $casts = [
        'date_processed' => 'date',
        'pay_month' => 'date',
        'basic_salary' => 'float',
        'allowance' => 'float',
        'overtime_pay' => 'float',
        'bonus' => 'float',
        'gross_pay' => 'float',
        'other_deductions' => 'float',
        'net_pay' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payroll) {
            $payroll->status = self::STATUS_PROCESSING;
            $payroll->payroll_id = 'PAY-' . date('Ym') . '-' . str_pad((static::count() + 1), 4, '0', STR_PAD_LEFT);
            
            // Get employee details
            $employee = Employee::find($payroll->employee_id);
            if ($employee) {
                // Set basic salary from employee record
                $payroll->basic_salary = $employee->basic_salary;
                
                // Calculate default allowance (10% of basic salary)
                $payroll->allowance = $payroll->basic_salary * 0.10;
                
                // Initialize other fields
                $payroll->overtime_hours = 0;
                $payroll->overtime_pay = 0;
                $payroll->bonus = 0;
                
                // Calculate gross pay
                $payroll->gross_pay = $payroll->basic_salary + $payroll->allowance;
                
                // Set processing date
                $payroll->date_processed = now();
            }
        });

        static::created(function ($payroll) {
            // Set initial status
            $payroll->status = self::STATUS_PENDING;
            $payroll->save();
        });

        static::saving(function ($payroll) {
            $precision = config('payroll.decimal_precision', 2);

            // Round monetary values for consistency
            $payroll->basic_salary = round($payroll->basic_salary ?? 0, $precision);
            $payroll->allowance = round($payroll->allowance ?? 0, $precision);
            $payroll->overtime_pay = round($payroll->overtime_pay ?? 0, $precision);
            $payroll->bonus = round($payroll->bonus ?? 0, $precision);
            
            // Calculate and round gross pay
            $payroll->gross_pay = round(
                $payroll->basic_salary +
                $payroll->allowance +
                $payroll->overtime_pay +
                $payroll->bonus,
                $precision
            );

            // If gross_pay has changed or deductions haven't been calculated yet
            if ($payroll->isDirty('gross_pay') || !$payroll->relationLoaded('deductions')) {
                // Ensure we're working with the latest deductions
                if (!$payroll->relationLoaded('deductions')) {
                    $payroll->load('deductions');
                }

                // Calculate deductions if they exist
                if ($payroll->deductions->isNotEmpty()) {
                    $deduction = $payroll->deductions->first();
                    // This will trigger the deduction's saving event to recalculate values
                    $deduction->save();
                }
            }

            // Calculate total deductions and update net pay with proper rounding
            if ($payroll->deductions->isNotEmpty()) {
                $totalDeductions = $payroll->deductions->sum('total_deduction');
                $payroll->net_pay = round($payroll->gross_pay - $totalDeductions, $precision);
            }
        });
    }

    /**
     * Calculate hourly rate based on basic salary
     *
     * @return float
     */
    public function calculateHourlyRate()
    {
        $workingDays = config('payroll.working_days_per_month', 22);
        $hoursPerDay = config('payroll.working_hours_per_day', 8);
        $precision = config('payroll.decimal_precision', 2);

        return round($this->basic_salary / ($workingDays * $hoursPerDay), $precision);
    }

    /**
     * Calculate overtime rate
     *
     * @return float
     */
    public function calculateOvertimeRate()
    {
        $multiplier = config('payroll.overtime_multiplier', 1.5);
        $precision = config('payroll.decimal_precision', 2);
        
        return round($this->calculateHourlyRate() * $multiplier, $precision);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
}
