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
            $this->tableName = "inventory_precount";
        }
        else{
            $this->tableName = "inventory_precount_houston";
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
                date_counted,
                time_counted,
                plus_minus,
                created_at,
                updated_at
             FROM ' . $this->tableName . ' ' . $where,
            $params
        );

        return json_encode($partData);
    }

}
