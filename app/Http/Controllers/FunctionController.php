<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunctionController extends Controller
{
    public $tableName;
    public $tableNamePre;

    public function __construct(){
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
            $this->tableNamePre = "inventory";
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
        'GS0',
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

    public function updatePreCount(Request $request){
        $updatePart = DB::update('
            UPDATE ' . $this->tableNamePre . '
            SET count = ?
            WHERE id = ?',
            [$request->count, $request->part]);

        $costs = DB::select('
            SELECT cost_counted, plus_minus FROM ' . $this->tableNamePre . ' WHERE id = ?',[$request->part]
        );
        return json_encode($costs);
    }

    public function getBins(){
        $bins = DB::select('
            SELECT DISTINCT
                bin
            FROM ' . $this->tableName
        );

        return $bins;
    }

    public function getWarehouses(){
        $warehouses = DB::select('
            SELECT DISTINCT
                warehouse
            FROM ' . $this->tableName
        );
        return $warehouses;
    }

    //  HELPER CLASS TO CONVERT EPICOR COMPANY CODES TO TEXT READABLE COMPANY NAMES
    public static function epicorCodeToCompanyName($code){
        switch($code){
            case "00":
                return "Andronaco Industries";
            case "10":
                return "PureFlex";
            case "20":
                return "Nil-Cor";
            case "30":
                return "Ethylene";
            case "40":
                return "Hills-McCanna";
            case "50":
                return "Ramparts Pumps";
            case "CC0":
                return "Conley Composites";
            case "FC0":
                return "FlowCor";
            case "G50":
                return "Endurance Composites";
            case "GWS":
                return "Great Western Supply";
            case "PV0":
                return "PolyValve";
        }
    }

}
