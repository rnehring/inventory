<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = session('location') === 'Kentwood' ? 'inventory' : 'inventory_houston';
    }

    /**
     * Get overall expected quantity reliability statistics
     */
    public function getOverallStats(): array
    {
        return Cache::remember("dashboard_stats_{$this->tableName}", 300, function() {
            $result = DB::table($this->tableName)
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
        });
    }

    /**
     * Get warehouse-by-warehouse breakdown
     */
    public function getWarehouseBreakdown(): array
    {
        return Cache::remember("dashboard_warehouse_{$this->tableName}", 300, function() {
            $results = DB::table($this->tableName)
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
        });
    }

    /**
     * Calculate overall reliability score
     */
    public function calculateReliabilityScore(array $stats): float
    {
        if ($stats['totalItems'] == 0) {
            return 0;
        }

        $accurateItems = $stats['expectedZeroCountedZero'] +
            ($stats['expectedNonZero'] - $stats['expectedNonZeroCountedZero']);

        return round(($accurateItems / $stats['totalItems']) * 100, 2);
    }

    /**
     * Get all-time count statistics by user
     */
    public function getAllTimeCountsByUser(): array
    {
        return Cache::remember("all_time_counts_{$this->tableName}", 600, function() {
            return DB::table($this->tableName . ' as ih')
                ->join('users as u', 'u.id', '=', 'ih.user')
                ->select(
                    'ih.user',
                    DB::raw('COUNT(user) as counts'),
                    'u.id',
                    'u.first_name',
                    'u.last_name'
                )
                ->whereNotNull('ih.user')
                ->groupBy('user', 'u.id', 'u.first_name', 'u.last_name')
                ->orderByDesc('counts')
                ->get()
                ->toArray();
        });
    }

    /**
     * Get warehouse value breakdown
     */
    public function getWarehouseValue(): array
    {
        return Cache::remember("warehouse_value_{$this->tableName}", 300, function() {
            $results = DB::table($this->tableName)
                ->select('warehouse')
                ->selectRaw('
                    COUNT(*) AS item_count,
                    SUM(cost_counted) AS total_value,
                    SUM(cost_expected) AS expected_value,
                    ROUND((SUM(cost_counted) / (SELECT SUM(cost_counted) FROM ' . $this->tableName . ')) * 100, 2) AS pct_of_total
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
        });
    }

    /**
     * Clear all dashboard caches
     */
    public function clearCache(): void
    {
        Cache::forget("dashboard_stats_{$this->tableName}");
        Cache::forget("dashboard_warehouse_{$this->tableName}");
        Cache::forget("all_time_counts_{$this->tableName}");
        Cache::forget("warehouse_value_{$this->tableName}");
    }
}
