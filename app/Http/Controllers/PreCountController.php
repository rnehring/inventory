<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PreCountController extends Controller
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
        return view('precount.index');
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
                ROUND(cost_counted - cost_expected, 2) AS plus_minus
             FROM(
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
                    ROUND(standard_cost * expected_qty, 2) AS cost_expected,
                    ROUND(standard_cost * count, 2) AS cost_counted
                FROM ' . $this->tableName . ') AS INV ' . $where,
            $params
        );

        return json_encode($partData);
    }

}
