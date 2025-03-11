<?php

namespace App\Http\Controllers;

abstract class Controller
{
    const KENTWOOD_COMPANIES = [
        '10',
        '20',
        '30',
        '40',
        '50',
        'PV0'
    ];

    const HOUSTON_COMPANIES = [
        'CC0',
        'FC0',
        'G50',
        'GWS',
        'DD'
    ];


    public static function formatCurrency($amount){
        $amount = floatval($amount);
        $formattedAmount = number_format($amount, 2, '.', '');
        $parts = explode('.', $formattedAmount);
        $parts[0] = number_format($parts[0]);
        return '$' . implode('.', $parts);
    }






}
