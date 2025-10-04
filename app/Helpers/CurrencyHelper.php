<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format a number as Nigerian Naira
     *
     * @param float|int $amount
     * @param int $decimals
     * @return string
     */
    public static function formatAsNaira($amount, $decimals = 2)
    {
        return '₦' . number_format($amount, $decimals);
    }
}