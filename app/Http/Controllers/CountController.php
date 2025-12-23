<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
        return view('count.index',['bins' => parent::getBins('inventory') ]);
    }

    public function getPart(Request $request){

        if(isset($request->part) && isset($request->bin)) {
            $where = 'WHERE part = ? AND bin = ?';
            $params = [$request->part, $request->bin];
        }
        if(isset($request->part) && !isset($request->bin)) {
            $where = 'WHERE part = ?';
            $params = [$request->part];
        }
        if(!isset($request->part) && isset($request->bin)) {
            $where = 'WHERE bin = ?';
            $params = [$request->bin];
        }

        $partData = DB::select('
            SELECT
                id,
                tag,
                tag_status,
                part,
                part_description,
                bin,
                warehouse,
                bin_description,
                lot_number,
                serial_number,
                count,
                by_weight,
                uom,
                `user`,
                date_counted,
                time_counted,
                note,
                expected_qty,
                standard_cost,
                cost_counted,
                cost_expected,
                plus_minus,
                top_eighty,
                created_at,
                updated_at,
                counted
            FROM ' . $this->tableName . ' ' . $where,
                $params
            );

        return json_encode($partData);
    }
}
