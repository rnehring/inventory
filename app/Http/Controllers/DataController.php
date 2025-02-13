<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class DataController extends Controller
{
    public function index() {

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
                FROM inventory) AS INV');

        $total = 0;

        foreach($allData as $data){
            $total += $data->plus_minus;
        }

        $allData = $this->paginate($allData, 30)->setPath('/data');
        $noTagTotal = $this->noTagTotals();
        return view('data.index', ['allData' => $allData, 'total' => $total, 'noTagTotal' => $noTagTotal]);
    }


    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    public function noTagTotals(){
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
                nt.plant,
                nt.lot_number,
                nt.serial_number,
                nt.user,
                p.price,
                ROUND(nt.count * p.price, 2) AS total
            FROM no_tag_parts nt
            JOIN part_prices p ON (nt.part = p.part)');

        $noTagTotal = 0;

        foreach($noTagData as $data){
            $noTagTotal += $data->total;
        }

        return $noTagTotal;
    }

}


