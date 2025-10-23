<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use League\Csv\Writer;
use League\Csv\Reader;

class DataController extends FunctionController
{

    public $tableName;
    public $ntTableName;
    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
            $this->ntTableName = "no_tag_parts";
        }
        else{
            $this->tableName = "inventory_houston";
            $this->ntTableName = "no_tag_parts_houston";
        }
    }
    public function index(Request $request) {

        $allData = DB::select('
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
                counted
            FROM '. $this->tableName);

        $total = 0;
        $totalPlusMinus = 0;
        foreach($allData as $data){
            $total += $data->cost_counted;
            $totalPlusMinus += $data->plus_minus;
        }

        $allData = $this->paginate($allData, 30)->setPath('/data');
        $noTagTotal = $this->noTagTotals($request);

        return view('data.index',
            [
                'allData' => $allData,
                'total' => $total,
                'totalPlusMinus' => $totalPlusMinus,
                'noTagTotal' => $noTagTotal
            ]);
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function noTagTotals(Request $request){

        if( is_array($request->companies) ){
            if( count($request->companies) == 1){
                if($request->companies[0] == "all"){
                    $where = "";
                }
                else{
                    $where = "WHERE nt.company = '" . $request->companies[0] . "'";
                }
            }
            else{
                $where = "WHERE nt.company IN(";
                foreach($request->companies AS $company){
                    $where .= "'" . $company . "',";
                }
                $where = substr($where, 0, -1);
                $where .= ")";
            }
        }
        else{
            $where = "";
        }

        $noTagData = DB::select('
            SELECT DISTINCT
                nt.id,
                nt.part,
                nt.bin,
                nt.count,
                nt.uom,
                nt.by_weight,
                nt.date_counted,
                nt.company,
                nt.warehouse,
                nt.lot_number,
                nt.serial_number,
                nt.user,
                p.price,
                ROUND(nt.count * p.price, 2) AS total
            FROM ' . $this->ntTableName . ' nt
            JOIN part_prices p ON (nt.part = p.part) ' . $where);

        $noTagTotal = 0;

        foreach($noTagData as $data){
            $noTagTotal += $data->total;
        }

        return $noTagTotal;
    }

    public function downloadData(Request $request){
        $where = $this->buildWhereClause($request);

        $allData = DB::select('
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
            FROM inventory ' . $where);

        foreach($allData as $data){
            $record = json_decode(json_encode($data), true);
            $allDataArray[] = $record;
        }

        $headers = [
            'id',
            'tag',
            'part',
            'part_description',
            'bin',
            'description',
            'company',
            'lot_number',
            'serial_number',
            'count',
            'user',
            'uom',
            'by_weight',
            'expected_qty',
            'standard_cost',
            'date_counted',
            'time_counted',
            'cost_expected',
            'cost_counted',
            'plus_minus'];

        //dd($allDataArray[0]);
        $filename = "dataexport.csv";
        $file_handle = fopen($filename, 'w');
        fclose($file_handle);

        $csv = Writer::from('dataexport.csv', 'w+');
        $csv->insertOne($headers);
        $csv->insertAll($allDataArray);

        $timestamp = date('YmdHis');

        if(!isset($request->companies)){
            $filename = "inventory_" . $timestamp . ".csv";
        }
        elseif(count($request->companies) > 1){
            $companyString = "";
            foreach($request->companies AS $company){
                $companyString .= parent::epicorCodeToCompanyName($company) . "_";
            }
            $companyString = strtolower(substr($companyString, 0, -1));
            $filename = $companyString . "_inventory_" . $timestamp . ".csv";
        }
        else{
            $filename = strtolower(parent::epicorCodeToCompanyName($request->companies[0])) . "_inventory_" . $timestamp . ".csv";
        }


        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $reader = Reader::from('dataexport.csv', 'r');
        $reader->download();
        die;
    }

    public function currentData(Request $request) {

        $where = $this->buildWhereClause($request);

        $allData = DB::select('
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
            FROM '. $this->tableName . ' ' . $where);

        $total = 0;

        foreach($allData as $data){
            $total += $data->plus_minus;
        }

        $allData = $this->paginate($allData, 30)->setPath('/company-data');
        $noTagTotal = $this->noTagTotals($request);

        return view('data.company-data',
            [
                'allData' => $allData,
                'total' => $total,
                'noTagTotal' => $noTagTotal,
                'currentCompanies' => $request->companies
            ]);
    }

    public function buildWhereClause(Request $request) {
        if( is_array($request->companies) ){
            if( count($request->companies) == 1){
                if($request->companies[0] == "all"){
                    $where = "";
                }
                else{
                    $where = "WHERE company = '" . $request->companies[0] . "'";
                }
            }
            else{
                $where = "WHERE company IN(";
                foreach($request->companies AS $company){
                    $where .= "'" . $company . "',";
                }
                $where = substr($where, 0, -1);
                $where .= ")";
            }
        }
        else{
            $where = "";
        }
        return $where;
    }
}


