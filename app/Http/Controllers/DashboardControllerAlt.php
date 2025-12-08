<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardControllerAlt extends FunctionController
{

    public function __construct()
    {
        parent::__construct();
        if (session()->get('location') == "Kentwood") {
            $this->tableName = "inventory";
            $this->tableNamePre = "precount";
            $this->ntTableName = "no_tag_parts";
        } else {
            $this->tableName = "inventory_houston";
            $this->tableNamePre = "precount_houston";
            $this->ntTableName = "no_tag_parts_houston";
        }
    }

    /**
     * Display the dashboard with Expected Qty Reliability metrics
     */
    public function index()
    {
        // Get overall statistics
        $stats = $this->getOverallStats();

        // Get warehouse breakdown
        $warehouseData = $this->getWarehouseBreakdown();

        // Calculate reliability score
        $reliabilityScore = $this->calculateReliabilityScore($stats);

        return view('dashboardalt', compact('stats', 'warehouseData', 'reliabilityScore'));
    }

    /**
     * Get overall expected quantity reliability statistics
     */
    private function getOverallStats()
    {
        $result = DB::table('inventory')
            ->selectRaw('
                -- Expected was zero scenarios
                SUM(CASE WHEN expected_qty = 0 AND count = 0 THEN 1 ELSE 0 END) AS expectedZeroCountedZero,
                SUM(CASE WHEN expected_qty = 0 AND count > 0 THEN 1 ELSE 0 END) AS expectedZeroCountedNonZero,

                -- Expected was non-zero scenarios
                SUM(CASE WHEN expected_qty > 0 AND count = 0 THEN 1 ELSE 0 END) AS expectedNonZeroCountedZero,
                SUM(CASE WHEN expected_qty > 0 AND count = expected_qty THEN 1 ELSE 0 END) AS expectedNonZeroCountedCorrect,
                SUM(CASE WHEN expected_qty > 0 AND count > 0 AND count != expected_qty THEN 1 ELSE 0 END) AS expectedNonZeroCountedWrong,

                -- Totals
                COUNT(*) AS totalItems,
                SUM(CASE WHEN expected_qty = 0 THEN 1 ELSE 0 END) AS expectedZero,
                SUM(CASE WHEN expected_qty > 0 THEN 1 ELSE 0 END) AS expectedNonZero
            ')
            ->first();

        return [
            'expectedZeroCountedZero' => $result->expectedZeroCountedZero ?? 0,
            'expectedZeroCountedNonZero' => $result->expectedZeroCountedNonZero ?? 0,
            'expectedNonZeroCountedZero' => $result->expectedNonZeroCountedZero ?? 0,
            'expectedNonZeroCountedCorrect' => $result->expectedNonZeroCountedCorrect ?? 0,
            'expectedNonZeroCountedWrong' => $result->expectedNonZeroCountedWrong ?? 0,
            'totalItems' => $result->totalItems ?? 0,
            'expectedZero' => $result->expectedZero ?? 0,
            'expectedNonZero' => $result->expectedNonZero ?? 0,
        ];
    }

    /**
     * Get warehouse-by-warehouse breakdown
     */
    private function getWarehouseBreakdown()
    {
        $results = DB::table('inventory')
            ->select('warehouse')
            ->selectRaw('
                COUNT(*) AS total,
                SUM(CASE WHEN expected_qty = 0 AND count > 0 THEN 1 ELSE 0 END) AS surpriseFinds,
                SUM(CASE WHEN expected_qty > 0 AND count = 0 THEN 1 ELSE 0 END) AS unexpectedEmpty,
                SUM(CASE WHEN (expected_qty = 0 AND count = 0) OR (expected_qty > 0 AND count > 0) THEN 1 ELSE 0 END) AS accurate
            ')
            ->groupBy('warehouse')
            ->orderByDesc('total')
            ->get();

        return $results->map(function ($item) {
            return [
                'warehouse' => $item->warehouse,
                'total' => $item->total ?? 0,
                'surpriseFinds' => $item->surpriseFinds ?? 0,
                'unexpectedEmpty' => $item->unexpectedEmpty ?? 0,
                'accurate' => $item->accurate ?? 0,
            ];
        })->toArray();
    }

    /**
     * Calculate overall reliability score
     */
    private function calculateReliabilityScore($stats)
    {
        if ($stats['totalItems'] == 0) {
            return 0;
        }

        $accurateItems = $stats['expectedZeroCountedZero'] +
            ($stats['expectedNonZero'] - $stats['expectedNonZeroCountedZero']);

        return ($accurateItems / $stats['totalItems']) * 100;
    }

    /**
     * Get surprise finds (items found where expected_qty = 0)
     * This can be used for a detailed drill-down page
     */
    public function surpriseFinds()
    {
        $items = DB::table('inventory')
            ->where('expected_qty', 0)
            ->where('count', '>', 0)
            ->select(
                'part',
                'part_description',
                'bin',
                'warehouse',
                'expected_qty',
                'count',
                'cost_counted',
                'user',
                'date_counted'
            )
            ->orderByDesc('cost_counted')
            ->paginate(50);

        return view('dashboardalt.surprise-finds', compact('items'));
    }

    /**
     * Get missing stock (items expected but count = 0)
     * This can be used for a detailed drill-down page
     */
    public function missingStock()
    {
        $items = DB::table('inventory')
            ->where('expected_qty', '>', 0)
            ->where('count', 0)
            ->select(
                'part',
                'part_description',
                'bin',
                'warehouse',
                'expected_qty',
                'count',
                'cost_expected',
                'user',
                'date_counted'
            )
            ->orderByDesc('cost_expected')
            ->paginate(50);

        return view('dashboardalt.missing-stock', compact('items'));
    }

    /**
     * API endpoint for getting dashboard data in JSON format
     * Useful for AJAX refreshes or chart updates
     */
    public function getData()
    {
        $stats = $this->getOverallStats();
        $warehouseData = $this->getWarehouseBreakdown();
        $reliabilityScore = $this->calculateReliabilityScore($stats);

        return response()->json([
            'stats' => $stats,
            'warehouseData' => $warehouseData,
            'reliabilityScore' => $reliabilityScore,
        ]);
    }

    public function allTimeCounts()
    {
        $allTimeCounts = DB::select('
            SELECT
                ih.user,
                COUNT(user) as counts,
                u.id,
                u.first_name as first_name,
                u.last_name as last_name
            FROM ' . $this->tableName . ' ih
            JOIN users u ON u.id = ih.user
            WHERE ih.user != ?
            GROUP BY user
            ORDER BY counts DESC',
            ['']);

        return json_encode($allTimeCounts);
    }


    public function percentageByCompany()
    {
        $percentageByCompany = DB::select('
            SELECT
                SUM(counted = ? )*100/count(*) AS percentage,
                company
            FROM ' . $this->tableName . '
            GROUP BY company',
            ['1']);

        $percentageByCompany = json_decode(json_encode($percentageByCompany), true);
        for ($i = 0; $i < count($percentageByCompany); $i++) {
            $percentageByCompany[$i]['company'] = $this->epicorCodeToCompanyName($percentageByCompany[$i]['company']);
        }

        return json_encode($percentageByCompany);
    }


    public function warehouseValue()
    {
        $results = DB::table('inventory')
            ->select('warehouse')
            ->selectRaw('
                COUNT(*) AS item_count,
                SUM(cost_counted) AS total_value,
                SUM(cost_expected) AS expected_value,
                ROUND((SUM(cost_counted) / (SELECT SUM(cost_counted) FROM inventory)) * 100, 2) AS pct_of_total
            ')
            ->groupBy('warehouse')
            ->orderByDesc('total_value')
            ->get();

        return $results->map(function ($item) {
            return [
                'warehouse' => $item->warehouse,
                'total' => $item->total_value ?? 0,
                'expected' => $item->expected_value ?? 0,
                'pct' => $item->pct_of_total ?? 0,
            ];
        })->toArray();
    }

}
