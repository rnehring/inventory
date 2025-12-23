<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FunctionController extends Controller
{
    public $tableName;
    public $tableNamePre;

    public $currentCompanies;

    public function __construct(){
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
            $this->tableNamePre = "inventory_precount";
        }
        else{
            $this->tableName = "inventory_houston";
            $this->tableNamePre = "inventory_precount_houston";
        }
    }

    public static function formatCurrency($amount){
        $amount = floatval($amount);
        $formattedAmount = number_format($amount, 2, '.', '');
        $parts = explode('.', $formattedAmount);
        $parts[0] = number_format($parts[0]);
        return '$' . implode('.', $parts);
    }

    public function updateCount(Request $request){
        $userId = Auth::id();
        $countTable = $request->path_info == '/pre-count' ? $this->tableNamePre : $this->tableName;

        $updatePart = DB::update('
            UPDATE ' . $countTable . '
            SET count = ?,
            user = ?
            WHERE id = ?',
            [$request->count, $userId, $request->part]);

        $costs = DB::select('
            SELECT
                cost_counted,
                plus_minus
            FROM ' . $this->tableName . '
                WHERE id = ?',[$request->part]
        );
        return json_encode($costs);
    }

    public function updatePreCount(Request $request){
        $updatePart = DB::update('
            UPDATE ' . $this->tableNamePre . '
            SET count = ?, bin_verified = ?, tag_status = 1, counted = 1
            WHERE id = ?',
            [$request->count, $request->bin_verified, $request->part]);

        $costs = DB::select('
            SELECT
                cost_counted,
                plus_minus
            FROM ' . $this->tableNamePre . '
            WHERE id = ?',[$request->part]
        );
        return json_encode($costs);
    }

    public function getBins($table){
        $bins = DB::select('
            SELECT DISTINCT
                bin
            FROM ' . $table . '
            ORDER BY bin ASC');

        return $bins;
    }

    public function getWarehouses($table){
        $warehouses = DB::select('
            SELECT DISTINCT
                warehouse
            FROM ' . $table . '
            ORDER BY warehouse ASC');
        return $warehouses;
    }

}
