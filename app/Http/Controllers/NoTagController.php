<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoTagController extends Controller
{

    public function index(){
        return view('notag.index');
    }


    public function getNoTagParts(){

    }



    public function saveNoTagPart(Request $request){

        $dateNow = date("Y-m-d");
        $timeNow = date("H:i:s");

        $addNoTagPart = DB::insert('
            INSERT INTO no_tag_parts(
                     part,
                     bin,
                     count,
                     uom,
                     by_weight,
                     company,
                     warehouse,
                     lot_number,
                     serial_number,
                     date_counted,
                     time_counted)
            VALUES(
                   ?,
                   ?,
                   ?,
                   ?,
                   ?,
                   ?,
                   ?,
                   ?,
                   ?,
                   ?,
                   ?)',
            [$request->part, $request->bin, $request->count, $request->uom, $request->by_weight, $request->company, $request->warehouse, $request->lot_number, $request->serial_number, $dateNow, $timeNow]);

        return true;
    }
}
