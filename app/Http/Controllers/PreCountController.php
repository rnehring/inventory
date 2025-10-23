<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreCountController extends FunctionController
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
        return view('precount.index', ['bins' => parent::getBins()]);
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
                plus_minus
             FROM ' . $this->tableName . ' ' . $where,
            $params
        );

        return json_encode($partData);
    }

}
