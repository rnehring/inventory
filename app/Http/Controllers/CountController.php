<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountController extends Controller
{


    public function index(){
        return view('count.index');
    }


    public function getPart(Request $request){

        $partData = DB::select('
            SELECT *
            FROM inventory
            WHERE part = ? AND bin = ?',
            [$request->part, $request->bin]);

        return json_encode($partData);
    }
}
