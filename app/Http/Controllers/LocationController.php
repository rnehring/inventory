<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{

    public $tableName;

    public function __construct()
    {
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
        }
        else{
            $this->tableName = "inventory_houston";
        }
    }

    public function index(){
        return view('location.index', ['warehouses' => $this->getWarehouses()]);
    }

    public function getWarehouses(){
        $warehouses = DB::select('
            SELECT DISTINCT
                warehouse
            FROM ' . $this->tableName
        );

        return $warehouses;
    }

    public function getPartsByLocation(Request $request){

        $bin = $request->bin . "%";

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
            WHERE bin LIKE ? AND warehouse = ?',
            [$bin, $request->warehouse]);

        return json_encode($partData);
    }


    public function updateCount(Request $request){
        $updatePart = DB::update('
            UPDATE ' . $this->tableName . '
            SET count = ?
            WHERE id = ?',
            [$request->count, $request->part]);

        return true;
    }
}
