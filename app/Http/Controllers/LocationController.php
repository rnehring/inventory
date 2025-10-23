<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends FunctionController
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
        return view('location.index',
            [
                'warehouses' => parent::getWarehouses(),
                'bins' => parent::getBins(),
            ]);
    }

    public function getPartsByLocation(Request $request){
        if($request->bin == "Choose a Bin"){
            $bin = "%";
        }
        else{
            $bin = $request->bin . "%";
        }

        if($request->warehouse == "Choose a Plant"){
            $warehouse = "%";
        }
        else{
            $warehouse = $request->warehouse . "%";
        }

        $partData = DB::select('
            SELECT
                id,
                tag,
                part,
                part_description,
                bin,
                description,
                company,
                lot_number,
                serial_number,
                count,
                user,
                uom,
                by_weight,
                expected_qty,
                standard_cost,
                date_counted,
                time_counted,
                cost_expected,
                cost_counted,
                warehouse,
                plus_minus,
                counted,
                top_eighty
            FROM ' . $this->tableName . '
            WHERE bin LIKE ? AND warehouse LIKE ?',
            [$bin, $warehouse]);
        return json_encode($partData);
    }
}
