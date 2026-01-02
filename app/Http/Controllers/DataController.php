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

    public $currentCompanies;

    public $functionController;

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
        $this->functionController = new FunctionController();
    }
//    public function index(Request $request) {
//
//        $allData = DB::select('
//            SELECT
//                id,
//                tag,
//                part,
//                part_description,
//                bin,
//                description,
//                company,
//                lot_number,
//                serial_number,
//                count,
//                user,
//                uom,
//                by_weight,
//                expected_qty,
//                standard_cost,
//                date_counted,
//                time_counted,
//                cost_expected,
//                cost_counted,
//                plus_minus,
//                counted
//            FROM '. $this->tableName);
//
//        $total = 0;
//        $totalPlusMinus = 0;
//        foreach($allData as $data){
//            $total += $data->cost_counted;
//            $totalPlusMinus += $data->plus_minus;
//        }
//
//        $allData = $this->paginate($allData, 30)->setPath('/data');
//        $noTagTotal = $this->noTagTotals($request);
//
//        return view('data.index',
//            [
//                'allData' => $allData,
//                'total' => $total,
//                'totalPlusMinus' => $totalPlusMinus,
//                'noTagTotal' => $noTagTotal
//            ]);
//    }

    public function index(Request $request) {

        $allData = DB::select('
            SELECT
                id,
                tag,
                tag_printed,
                part,
                bin,
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

        $noTagTotal = $this->noTagTotals($request);

        return view('data.index',
            [
                'allData' => $allData,
                'total' => $total,
                'totalPlusMinus' => $totalPlusMinus,
                'noTagTotal' => $noTagTotal
            ]);
    }

    public function getAllData(Request $request) {
        $allData = DB::select('
            SELECT
                i.id,
                i.tag,
                i.tag_printed,
                i.part,
                i.bin,
                i.lot_number,
                i.serial_number,
                i.count,
                i.user,
                i.uom,
                i.warehouse,
                i.by_weight,
                i.expected_qty,
                i.standard_cost,
                i.date_counted,
                i.time_counted,
                i.cost_expected,
                i.cost_counted,
                i.plus_minus,
                i.counted,
                u.initials
            FROM '. $this->tableName . ' i
            LEFT JOIN users u ON i.user = u.id');

            $allData = json_decode(json_encode($allData), true);
            return json_encode($allData);
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

        $noTagData = DB::select('
            SELECT
                SUM(cost_counted) as total_cost
            FROM ' . $this->ntTableName . ' ' . $where);

        return $noTagData[0]->total_cost;
    }

    public function downloadData(Request $request){
        $where = $this->buildWhereClause($request);

        $allData = DB::select('
            SELECT
                id,
                tag,
                tag_status,
                part,
                part_description,
                bin,
                bin_description,
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
            FROM ' . $this->tableName . ' ' . $where);

        foreach($allData as $data){
            $record = json_decode(json_encode($data), true);
            $allDataArray[] = $record;
        }

        $headers = [
            'id',
            'tag',
            'tag_status',
            'part',
            'part_description',
            'bin',
            'bin_description',
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
            'plus_minus'
        ];

        $filename = "dataexport.csv";
        $file_handle = fopen($filename, 'w');
        fclose($file_handle);

        $csv = Writer::from('dataexport.csv', 'w+');
        $csv->insertOne($headers);
        $csv->insertAll($allDataArray);

        $timestamp = date('YmdHis');

        $filename = "inventory_" . $timestamp . ".csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $reader = Reader::from('dataexport.csv', 'r');
        $reader->download();
        die;
    }

    public function currentData(Request $request) {


        $allData = DB::select('
            SELECT
                id,
                tag,
                tag_status,
                part,
                part_description,
                bin,
                bin_description,
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
        $totalPlusMinus = 0;
        foreach($allData as $data){
            $total += $data->cost_counted;
            $totalPlusMinus += $data->plus_minus;
        }

        $allData = $this->paginate($allData, 30)->setPath('/company-data');
        $noTagTotal = $this->noTagTotals($request);

        return view('data.company-data',
            [
                'allData' => $allData,
                'total' => $total,
                'noTagTotal' => $noTagTotal,

                'totalPlusMinus' => $totalPlusMinus,
            ]);
    }

    /**
     * Export current inventory to CSV
     */
    public function exportInventory()
    {
        $allData = DB::select('
            SELECT
                id,
                tag,
                tag_printed,
                part,
                bin,
                warehouse,
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
            FROM ' . $this->tableName);

        $allDataArray = [];
        foreach($allData as $data){
            $record = json_decode(json_encode($data), true);
            $allDataArray[] = $record;
        }

        $headers = [
            'id',
            'tag',
            'tag_printed',
            'part',
            'bin',
            'warehouse',
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
            'plus_minus',
            'counted'
        ];

        $tempFile = tempnam(sys_get_temp_dir(), 'inventory_export_');
        $csv = Writer::createFromPath($tempFile, 'w+');
        $csv->insertOne($headers);
        $csv->insertAll($allDataArray);

        $timestamp = date('YmdHis');
        $filename = "inventory_export_" . $timestamp . ".csv";

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export no-tag parts to CSV
     */
    public function exportNoTagData()
    {
        $allData = DB::select('
            SELECT
                id,
                tag,
                part,
                bin,
                warehouse,
                count,
                uom,
                by_weight,
                lot_number,
                serial_number,
                user,
                note,
                date_counted,
                time_counted,
                expected_qty,
                standard_cost,
                cost_counted,
                plus_minus
            FROM ' . $this->ntTableName);

        $allDataArray = [];
        foreach($allData as $data){
            $record = json_decode(json_encode($data), true);
            $allDataArray[] = $record;
        }

        $headers = [
            'id',
            'tag',
            'part',
            'bin',
            'warehouse',
            'count',
            'uom',
            'by_weight',
            'lot_number',
            'serial_number',
            'user',
            'note',
            'date_counted',
            'time_counted',
            'expected_qty',
            'standard_cost',
            'cost_counted',
            'plus_minus'
        ];

        $tempFile = tempnam(sys_get_temp_dir(), 'notag_export_');
        $csv = Writer::createFromPath($tempFile, 'w+');
        $csv->insertOne($headers);
        $csv->insertAll($allDataArray);

        $timestamp = date('YmdHis');
        $filename = "no_tag_parts_export_" . $timestamp . ".csv";

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }


}


