<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'department_id',
        'position',
        'date_hired',
        'basic_salary',
        'allowance',
        'bank_name',
        'account_number',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_hired' => 'date',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function deductions()
    {
        return $this->hasManyThrough(Deduction::class, Payroll::class);
    }

    public function getLatestDeductionsAttribute()
    {
        return $this->payrolls()
            ->latest()
            ->first()
            ?->deduction;
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function managedDepartment()
    {
        return $this->hasOne(Department::class, 'manager_id');
    }

    public function calculateNetSalary()
    {
        $gross_pay = $this->basic_salary + $this->allowance;
        
        // Get latest deductions
        $latest_payroll = $this->payrolls()->latest()->first();
        
        if ($latest_payroll && $latest_payroll->deduction) {
            $deductions = $latest_payroll->deduction;
            return $gross_pay - $deductions->total_deduction;
        }

        // If no deductions found, calculate default deductions
        $tax = $this->calculateDefaultTax($gross_pay);
        $pension = $this->basic_salary * 0.08; // 8% pension
        
        return $gross_pay - ($tax + $pension);
    }

    private function calculateDefaultTax($gross_pay)
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
}