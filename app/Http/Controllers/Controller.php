<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public static function formatCurrency($amount){
        $amount = floatval($amount);
        $formattedAmount = number_format($amount, 2, '.', '');
        $parts = explode('.', $formattedAmount);
        $parts[0] = number_format($parts[0]);
        return '$' . implode('.', $parts);
    }






}
