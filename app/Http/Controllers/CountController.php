<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CountController extends Controller
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
        $bins = $this->getBins();
        return view('count.index',['bins' => $bins ]);
    }

    public function getBins(){
        $bins = DB::select('
            SELECT DISTINCT
                bin
            FROM ' . $this->tableName
        );

        return $bins;
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
                plus_minus,
                counted,
                top_eighty
            FROM ' . $this->tableName . ' ' . $where,
                $params
            );

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
