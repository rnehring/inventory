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
        return view('precount.index', ['bins' => parent::getBins('inventory_precount')]);
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

        $partData = DB::select("
            SELECT
                id,
                IFNULL(tag, '') AS tag,
                IFNULL(tag_status, '') AS tag_status,
                part,
                part_description,
                warehouse,
                bin,
                bin_description,
                bin_verified,
                verified_date,
                count,
                uom,
                by_weight,
                IFNULL(lot_number, '') AS lot_number,
                IFNULL(serial_number, '') AS serial_number,
                `user`,
                expected_qty,
                standard_cost,
                cost_counted,
                cost_expected,
                date_counted,
                time_counted,
                plus_minus,
                created_at,
                updated_at,
                top_eighty,
                IF(top_eighty = 1, '" . view('components.top-eighty-star')->render() . "', '') AS top_eighty_star,
                counted
             FROM " . $this->tableName . " " . $where,
            $params
        );

        return json_encode($partData);
    }

}
