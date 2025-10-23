<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use App\Models\InventoryUpload;

class UploadController extends FunctionController
{

    public $tableName;

    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
        }
        else{
            $this->tableName = "inventory_houston";
        }
    }

    public function index(){
        return view('upload.index');
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
                plus_minus
            FROM ' . $this->tableName . ' ' . $where,
            $params
        );

        return json_encode($partData);
    }


    public function processUpload(Request $request){

        $csv = Reader::from(request()->file('csvfile')->getRealPath(), 'r');
        $csv->setHeaderOffset(0);

        $parts = [];

        $deleted = InventoryUpload::query()->delete();

        foreach ($csv as $part) {
            $date_counted = date_create($part['date_counted']);
            $date_counted = date_format($date_counted, 'Y-m-d');
            $time_counted = date_create($part['time_counted']);
            $time_counted = date_format($time_counted, 'H:i:s');
            $period_start_date = date_create($part['period_start_date']);
            $period_start_date = date_format($period_start_date, 'Y-m-d H:i:s');
            $period_end_date = date_create($part['period_end_date']);
            $period_end_date = date_format($period_end_date, 'Y-m-d H:i:s');

            $cycle = 0;

            $parts[] = [
                'tag' => $part['tag'],
                'part' => $part['part'],
                'part_description' => $part['part_description'],
                'bin' => $part['bin'],
                'description' => $part['description'],
                'lot_number' => $part['lot_number'],
                'serial_number' => $part['serial_number'],
                'count' => $part['count'],
                'by_weight' => $part['by_weight'],
                'uom' => $part['uom'],
                'activity_before_count' => $part['activity_before_count'],
                'returned' => $part['returned'],
                'user' => $part['user'],
                'date_counted' => $date_counted,
                'time_counted' => $time_counted,
                'note' => $part['note'],
                'has_transactions' => $part['has_transactions'],
                'sheet_number' => $part['sheet_number'],
                'tag_status' => $part['tag_status'],
                'enable_uom_worksheet' => $part['enable_uom_worksheet'],
                'period_end_date' => $period_end_date,
                'period_start_date' => $period_start_date,
                'cycle_period' => $part['cycle_period'],
                'company' => $part['company'],
                'warehouse' => $part['warehouse'],
                'expected_qty' => $part['expected_qty'],
                'standard_cost' => $part['standard_cost'],
                'created_at' => now(),
                'updated_at' => now(),
                'counted' => 0
            ];

            if (count($parts) === 1000){
                InventoryUpload::insert($parts);
                $parts = [];
                $cycle++;
            }
        }

        InventoryUpload::insert($parts);

        return redirect('/review');
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    public function reviewUpload(Request $request){
        $allParts = InventoryUpload::all();
        $totalParts = count($allParts);
        $allParts = $this->paginate($allParts, 30)->setPath('/review');
        return view('upload.review',
            [
                'allParts' => $allParts,
                'totalParts' => $totalParts
            ]);
    }

    public function saveUpload(Request $request){
        $copyCurrentProdData = "CALL backup_inventory_upload('" . $this->tableName . "');";
        DB::statement($copyCurrentProdData);

        $clearProdData = 'TRUNCATE TABLE ' . $this->tableName;
        DB::statement($clearProdData);

        $copyToProd = "CALL copy_upload_to_inventory('" . $this->tableName . "');";
        DB::statement($copyToProd);

        $deleteTrigger = "DROP TRIGGER IF EXISTS calculate_inventory_costs_before_update_" . strtolower(session()->get('location')) . ";";
        DB::statement($deleteTrigger);

        $setTopEighty = "CALL update_top_eighty('" . $this->tableName . "');";
        DB::statement($setTopEighty);

        DB::unprepared("
            DROP TRIGGER IF EXISTS calculate_inventory_costs_before_update_houston;

            CREATE DEFINER=`rnehring`@`%` TRIGGER `calculate_inventory_costs_before_update_" . strtolower(session()->get('location')) . "`
            BEFORE UPDATE ON `" . $this->tableName . "`
            FOR EACH ROW
            BEGIN
                SET NEW.counted = 1;
                SET NEW.cost_expected = NEW.expected_qty * NEW.standard_cost;
                SET NEW.cost_counted = NEW.count * NEW.standard_cost;
                SET NEW.plus_minus = NEW.cost_counted - NEW.cost_expected;
            END
        ");
        return view('upload.saved');
    }
}
