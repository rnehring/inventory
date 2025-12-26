<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationPreController extends FunctionController
{

    public $tableName;

    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory_precount";
        }
        else{
            $this->tableName = "inventory_precount_houston";
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
                tag,
                tag_status,
                part,
                part_description,
                warehouse,
                bin,
                bin_description,
                bin_verified,
                verified_date,
                count,
                by_weight,
                uom,
                lot_number,
                serial_number,
                `user`,
                expected_qty,
                standard_cost,
                cost_counted,
                cost_expected,
                plus_minus,
                counted,
                top_eighty,
                IF(top_eighty = 1, '" . view('components.top-eighty-star')->render() . "', '') AS top_eighty_star
             FROM " . $this->tableName . "
            WHERE bin LIKE ? AND warehouse LIKE ?",
            [$bin, $warehouse]);
        return json_encode($partData);
    }
}


