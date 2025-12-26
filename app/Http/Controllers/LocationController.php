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
                'warehouses' => parent::getWarehouses($this->tableName),
                'bins' => parent::getBins($this->tableName),
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
            FROM " . $this->tableName . " WHERE bin LIKE ? AND warehouse LIKE ?",
            [$bin, $warehouse]);
        return json_encode($partData);
    }
}
