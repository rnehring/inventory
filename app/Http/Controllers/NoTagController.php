<?php

namespace App\Http\Controllers;

use App\Models\NoTagPart;
use App\Models\NoTagPartHouston;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NoTagController extends FunctionController
{
    public $tableName;
    public $ntTableName;
    public $ntPreTableName;
    public $className;
    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->ntTableName = "no_tag_parts";
            $this->ntPreTableName = "no_tag_part_precount";
            $this->tableName = "inventory";
            $this->className = "NoTagPart";
        }
        else{
            $this->ntTableName = "no_tag_parts_houston";
            $this->ntPreTableName = "no_tag_part_houston_precount";
            $this->tableName = "inventory_houston";
            $this->className = "NoTagPartHouston";
        }
    }

    public function index(){
        return view('notag.index',['warehouses' => FunctionController::getWarehouses(), 'noTagParts' => NoTagPart::all()]);
    }

    public function editNoTag(Request $request){
        return view('notag.edit',['warehouses' => FunctionController::getWarehouses(), 'noTagPart' => NoTagPart::findOrFail($request->id)]);
    }

    public function update(Request $request){
        $noTagPart = NoTagPart::findOrFail($request->id);

        $noTagPart->fill($request->all());
        $noTagPart->save();

        return redirect()->route('notag.index')->with('success', 'Part updated successfully');
    }
    public function saveNoTagPart(Request $request){

        $dateNow = date("Y-m-d");
        $timeNow = date("H:i:s");
        $userId = Auth::id();
        $partPrice = DB::select('SELECT price FROM part_prices_houston WHERE part = ?', [$request->part]);

        $costCounted = $partPrice[0]->price * $request->count;

        DB::insert('
            INSERT INTO ' . $this->ntTableName . '(
                     part,
                     bin,
                     count,
                     uom,
                     by_weight,
                     warehouse,
                     lot_number,
                     serial_number,
                     user,
                     date_counted,
                     time_counted,
                     standard_cost,
                     cost_counted
                     )
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
                   ?,
                   ?,
                   ?)',
            [
                $request->part,
                $request->bin,
                $request->count,
                $request->uom,
                $request->by_weight,
                $request->warehouse,
                $request->lot_number,
                $request->serial_number,
                $userId,
                $dateNow,
                $timeNow,
                $partPrice[0]->price,
                $costCounted
            ]);

        if( session()->get('location') == "Kentwood"){
            $lastRecord = NoTagPart::latest()->first();
        }
        else{
            $lastRecord = NoTagPartHouston::latest()->first();
        }

        return $lastRecord;
    }
}
