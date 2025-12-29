<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount): string
    {
        if ($amount === null) {
            return '$0.00';
        }
        
        return '$' . number_format((float)$amount, 2, '.', ',');
    }

    public static function formatWithoutSymbol($amount): string
    {
        if ($amount === null) {
            return '0.00';
        }
        
        return number_format((float)$amount, 2, '.', ',');
    }
}
