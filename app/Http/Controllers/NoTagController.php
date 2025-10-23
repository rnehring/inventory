<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoTagController extends FunctionController
{
    public $tableName;

    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "no_tag_parts";
        }
        else{
            $this->tableName = "no_tag_parts_houston";
        }
    }

    public function index(){
        return view('notag.index');
    }

    public function getNoTagParts(){

    }

    public function saveNoTagPart(Request $request){

        $dateNow = date("Y-m-d");
        $timeNow = date("H:i:s");

        DB::insert('
            INSERT INTO ' . $this->tableName . '(
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
            [
                $request->part,
                $request->bin,
                $request->count,
                $request->uom,
                $request->by_weight,
                $request->company,
                $request->warehouse,
                $request->lot_number,
                $request->serial_number,
                $dateNow,
                $timeNow
            ]);

        return true;
    }
}
