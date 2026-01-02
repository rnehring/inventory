<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CountController extends FunctionController
{

    public $tableName;
    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
        }
        else{
            $this->tableName = "inventory_houston";
        }
    }

    public function index(){
        return view('count.index');
    }

    public function getPart(Request $request){
        $warehouse = session('plant');

        if(isset($request->part) && isset($request->bin)) {
            $where = 'WHERE part = ? AND bin = ?';
            $params = [$request->part, $request->bin];
            if( !Auth::user()->user_type->canManageUsers()) {
                $where .= ' AND warehouse = ?';
                $params = [$request->part, $request->bin, $warehouse];
            }
        }
        if(isset($request->part) && !isset($request->bin)) {
            $where = 'WHERE part = ?';
            $params = [$request->part];
            if( !Auth::user()->user_type->canManageUsers()) {
                $where .= ' AND warehouse = ?';
                $params = [$request->part,  $warehouse];
            };
        }
        if(!isset($request->part) && isset($request->bin)) {
            $where = 'WHERE bin = ?';
            $params = [$request->bin];
            if( !Auth::user()->user_type->canManageUsers()) {
                $where .= ' AND warehouse = ?';
                $params = [$request->bin, $warehouse];
            }
        }

        $partData = DB::select("
            SELECT
                id,
                IFNULL(tag, '') AS tag,
                IFNULL(tag_printed, '') AS tag_printed,
                part,
                bin,
                warehouse,
                IFNULL(lot_number, '') AS lot_number,
                IFNULL(serial_number, '') AS serial_number,
                count,
                by_weight,
                uom,
                `user`,
                expected_qty,
                standard_cost,
                cost_counted,
                cost_expected,
                plus_minus,
                top_eighty,
                counted,
                IF(top_eighty = 1, '" . view('components.top-eighty-star')->render() . "', '') AS top_eighty_star
            FROM " . $this->tableName . " " . $where,
                $params
            );

        return json_encode($partData);
    }
}
