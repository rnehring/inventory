<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{


    public function index(){
        return view('location.index');
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
                    ROUND(standard_cost * count, 2) AS cost_counted,
                    warehouse
                FROM inventory) AS INV
            WHERE bin LIKE ? AND warehouse = ?',
            [$bin, $request->warehouse]);

        return json_encode($partData);
    }


    public function updateCount(Request $request){
        $updatePart = DB::update('
            UPDATE inventory
            SET count = ?
            WHERE id = ?',
            [$request->count, $request->part]);

        return true;
    }
}
