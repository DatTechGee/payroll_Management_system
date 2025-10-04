<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'payroll_id',
        'tax',
        'tax_rate',
        'pension',
        'pension_rate',
        'other_deductions',
        'other_deductions_description',
        'total_deduction',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tax' => 'float',
        'pension' => 'float',
        'other_deductions' => 'float',
        'total_deduction' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deduction) {
            // Check for existing deduction for this payroll
            if (static::where('payroll_id', $deduction->payroll_id)->exists()) {
                throw new \RuntimeException('A deduction record already exists for this payroll.');
            }
        });

        static::saving(function ($deduction) {
            $precision = config('payroll.decimal_precision', 2);
            
            // Always round values for consistency
            $deduction->tax = round($deduction->tax ?? 0, $precision);
            $deduction->pension = round($deduction->pension ?? 0, $precision);
            $deduction->other_deductions = round($deduction->other_deductions ?? 0, $precision);
            
            // Always recalculate total deduction
            $deduction->total_deduction = round(
                $deduction->tax +
                $deduction->pension +
                ($deduction->other_deductions ?? 0),
                $precision
            );
        });
    }

    /**
     * Get the tax rate percentage for display
     *
     * @return float
     */
    public function getTaxRatePercentage()
    {
        if (!$this->payroll || !$this->tax) {
            return config('payroll.tax_rate', 0.1) * 100;
        }
        return round(($this->tax / $this->payroll->gross_pay) * 100, 2);
    }

    /**
     * Get the pension rate percentage for display
     *
     * @return float
     */
    public function getPensionRatePercentage()
    {
        if (!$this->payroll || !$this->pension) {
            return config('payroll.pension_rate', 0.05) * 100;
        }
        return round(($this->pension / $this->payroll->gross_pay) * 100, 2);
    }

    /**
     * Calculate tax amount based on rate and gross pay
     *
     * @param float $rate Percentage rate (0-100)
     * @param float $grossPay
     * @return float
     */
    public static function calculateTaxAmount($rate, $grossPay)
    {
        return round($grossPay * ($rate / 100), 2);
    }

    /**
     * Calculate pension amount based on rate and gross pay
     *
     * @param float $rate Percentage rate (0-100)
     * @param float $grossPay
     * @return float
     */
    public static function calculatePensionAmount($rate, $grossPay)
    {
        return round($grossPay * ($rate / 100), 2);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}