<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Tax Rate
    |--------------------------------------------------------------------------
    |
    | This value is the default tax rate used for payroll calculations.
    | The value should be between 0 and 1 (e.g., 0.1 for 10%)
    |
    */
    'tax_rate' => env('PAYROLL_TAX_RATE', 0.1),

    /*
    |--------------------------------------------------------------------------
    | Default Pension Rate
    |--------------------------------------------------------------------------
    |
    | This value is the default pension rate used for payroll calculations.
    | The value should be between 0 and 1 (e.g., 0.05 for 5%)
    |
    */
    'pension_rate' => env('PAYROLL_PENSION_RATE', 0.05),

    /*
    |--------------------------------------------------------------------------
    | Decimal Precision
    |--------------------------------------------------------------------------
    |
    | The number of decimal places to use for monetary calculations
    |
    */
    'decimal_precision' => 2,

    /*
    |--------------------------------------------------------------------------
    | Working Days Per Month
    |--------------------------------------------------------------------------
    |
    | The default number of working days in a month, used for calculating
    | daily and hourly rates
    |
    */
    'working_days_per_month' => env('PAYROLL_WORKING_DAYS', 22),

    /*
    |--------------------------------------------------------------------------
    | Working Hours Per Day
    |--------------------------------------------------------------------------
    |
    | The default number of working hours in a day, used for calculating
    | hourly rates
    |
    */
    'working_hours_per_day' => env('PAYROLL_HOURS_PER_DAY', 8),

    /*
    |--------------------------------------------------------------------------
    | Overtime Multiplier
    |--------------------------------------------------------------------------
    |
    | The multiplier used to calculate overtime pay rate
    | (e.g., 1.5 means overtime is paid at 1.5x normal rate)
    |
    */
    'overtime_multiplier' => env('PAYROLL_OVERTIME_MULTIPLIER', 1.5),
];