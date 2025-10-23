<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public $tableName;

    public function __construct(){
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
        }
        else{
            $this->tableName = "inventory_houston";
        }
    }
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



    public function updateCount(Request $request){
        $updatePart = DB::update('
            UPDATE ' . $this->tableName . '
            SET count = ?
            WHERE id = ?',
            [$request->count, $request->part]);

        $costs = DB::select('
            SELECT cost_counted, plus_minus FROM ' . $this->tableName . ' WHERE id = ?',[$request->part]
        );

        return json_encode($costs);
    }


}
