<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use App\Traits\UsesLocationTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends FunctionController
{
    use UsesLocationTables;

    protected $statsService;

    public function __construct(DashboardStatsService $statsService)
    {
        parent::__construct();
        $this->statsService = $statsService;

        $location = session()->get('location', 'Kentwood');
        $this->tableName = $location === "Kentwood" ? "inventory" : "inventory_houston";
        $this->tableNamePre = $location === "Kentwood" ? "inventory_precount" : "inventory_precount_houston";
        $this->ntTableName = $location === "Kentwood" ? "no_tag_parts" : "no_tag_parts_houston";
    }

    /**
     * Display the dashboard with Expected Qty Reliability metrics
     */
    public function index()
    {
        $stats = $this->statsService->getOverallStats();
        $warehouseData = $this->statsService->getWarehouseBreakdown();
        $reliabilityScore = $this->statsService->calculateReliabilityScore($stats);

        return view('dashboard', compact('stats', 'warehouseData', 'reliabilityScore'));
    }

    /**
     * Get surprise finds (items found where expected_qty = 0)
     */
    public function surpriseFinds()
    {
        $items = DB::table($this->tableName)
            ->where('expected_qty', 0)
            ->where('count', '>', 0)
            ->select(
                'part',
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

        return view('dashboard.surprise-finds', compact('items'));
    }

    /**
     * Get missing stock (items expected but count = 0)
     */
    public function missingStock()
    {
        $items = DB::table($this->tableName)
            ->where('expected_qty', '>', 0)
            ->where('count', 0)
            ->select(
                'part',
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

        return view('dashboard.missing-stock', compact('items'));
    }

    /**
     * API endpoint for getting dashboard data in JSON format
     */
    public function getData()
    {
        $stats = $this->statsService->getOverallStats();
        $warehouseData = $this->statsService->getWarehouseBreakdown();
        $reliabilityScore = $this->statsService->calculateReliabilityScore($stats);

        return response()->json([
            'stats' => $stats,
            'warehouseData' => $warehouseData,
            'reliabilityScore' => $reliabilityScore,
        ]);
    }

    /**
     * Get all-time counts by user
     */
    public function allTimeCounts()
    {
        $counts = $this->statsService->getAllTimeCountsByUser();
        return response()->json($counts);
    }

    /**
     * Get percentage by company
     */
    public function percentageByCompany()
    {
        $percentageByCompany = DB::table($this->tableName)
            ->select('company')
            ->selectRaw('SUM(counted = 1) * 100 / COUNT(*) AS percentage')
            ->groupBy('company')
            ->get();

        $results = $percentageByCompany->map(function($item) {
            return [
                'company' => $this->epicorCodeToCompanyName($item->company),
                'percentage' => $item->percentage,
            ];
        });

        return response()->json($results);
    }

    /**
     * Get warehouse value breakdown
     */
    public function warehouseValue()
    {
        $results = $this->statsService->getWarehouseValue();
        return response()->json($results);
    }

    /**
     * ABC Analysis / Pareto Chart
     * Top 20% of PARTS (by count) should represent ~80% of VALUE
     */
    public function abcAnalysis()
    {
        // Get all parts ordered by value descending
        $allParts = DB::table($this->tableName)
            ->where('cost_expected', '>', 0)
            ->orderByDesc('cost_expected')
            ->get(['id', 'cost_expected', 'plus_minus', 'counted']);

        $totalParts = $allParts->count();
        $cutoffIndex = (int)($totalParts * 0.2); // Top 20% of parts

        // Split: Top 20% of parts = A Items, Bottom 80% = B/C Items
        $aItems = $allParts->take($cutoffIndex);
        $bcItems = $allParts->skip($cutoffIndex);

        // Calculate stats for A items
        $aStats = [
            'category' => 'A Items (Top 20%)',
            'part_count' => $aItems->count(),
            'total_value' => $aItems->sum('cost_expected'),
            'total_variance' => $aItems->sum(fn($p) => abs($p->plus_minus ?? 0)),
            'percent_counted' => $aItems->count() > 0
                ? round($aItems->where('counted', 1)->count() / $aItems->count() * 100, 1)
                : 0
        ];

        // Calculate stats for B/C items
        $bcStats = [
            'category' => 'B/C Items (Bottom 80%)',
            'part_count' => $bcItems->count(),
            'total_value' => $bcItems->sum('cost_expected'),
            'total_variance' => $bcItems->sum(fn($p) => abs($p->plus_minus ?? 0)),
            'percent_counted' => $bcItems->count() > 0
                ? round($bcItems->where('counted', 1)->count() / $bcItems->count() * 100, 1)
                : 0
        ];

        return response()->json([$aStats, $bcStats]);
    }

    /**
     * Variance Distribution Chart
     * Shows count accuracy in color-coded zones
     */
    public function varianceDistribution()
    {
        $data = DB::table($this->tableName)
            ->selectRaw("
                CASE
                    WHEN counted = 0 THEN 'Not Counted'
                    WHEN ABS(plus_minus / NULLIF(cost_expected, 0)) < 0.05 THEN 'Excellent (±0-5%)'
                    WHEN ABS(plus_minus / NULLIF(cost_expected, 0)) < 0.10 THEN 'Good (±5-10%)'
                    WHEN ABS(plus_minus / NULLIF(cost_expected, 0)) < 0.25 THEN 'Fair (±10-25%)'
                    ELSE 'Poor (±25%+)'
                END as accuracy_range,
                COUNT(*) as count,
                SUM(cost_expected) as total_value,
                SUM(ABS(plus_minus)) as total_variance
            ")
            ->where('expected_qty', '>', 0)
            ->groupBy(DB::raw('accuracy_range'))
            ->orderByRaw("
                CASE accuracy_range
                    WHEN 'Excellent (±0-5%)' THEN 1
                    WHEN 'Good (±5-10%)' THEN 2
                    WHEN 'Fair (±10-25%)' THEN 3
                    WHEN 'Poor (±25%+)' THEN 4
                    WHEN 'Not Counted' THEN 5
                END
            ")
            ->get();

        return response()->json($data);
    }

    /**
     * Count Velocity Timeline
     * Shows parts counted per day over last 30 days
     */
    public function countVelocity()
    {
        $data = DB::table($this->tableName)
            ->selectRaw("
                DATE(date_counted) as count_date,
                COUNT(*) as parts_counted,
                SUM(cost_counted) as value_counted,
                COUNT(DISTINCT user) as active_counters
            ")
            ->whereRaw('date_counted >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)')
            ->where('counted', 1)
            ->groupBy(DB::raw('DATE(date_counted)'))
            ->orderBy('count_date', 'ASC')
            ->get();

        return response()->json($data);
    }

    /**
     * Top Bins by Value
     * Shows the 15 most valuable bin locations
     */
    public function topBinsByValue()
    {
        $data = DB::table($this->tableName)
            ->select('bin', 'warehouse')
            ->selectRaw('SUM(cost_expected) as total_value')
            ->selectRaw('SUM(cost_counted) as counted_value')
            ->selectRaw('COUNT(*) as part_count')
            ->selectRaw('SUM(CASE WHEN counted = 1 THEN 1 ELSE 0 END) as counted_parts')
            ->where('cost_expected', '>', 0)
            ->groupBy('bin', 'warehouse')
            ->orderByDesc('total_value')
            ->limit(15)
            ->get();

        return response()->json($data);
    }

    /**
     * Warehouse Progress Comparison
     * Shows completion status by warehouse with multiple metrics
     */
    public function warehouseProgress()
    {
        $data = DB::table($this->tableName)
            ->select('warehouse')
            ->join('plants', 'plants.plant', '=', $this->tableName . '.warehouse')
            ->selectRaw('COUNT(*) as total_parts')
            ->selectRaw('SUM(CASE WHEN counted = 1 THEN 1 ELSE 0 END) as counted_parts')
            ->selectRaw('SUM(cost_expected) as total_value')
            ->selectRaw('SUM(cost_counted) as counted_value')
            ->selectRaw('SUM(ABS(plus_minus)) as total_variance')
            ->selectRaw('ROUND(SUM(CASE WHEN counted = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as completion_percent')
            ->where('plants.active', 1)
            ->groupBy('warehouse')
            ->orderByDesc('completion_percent')
            ->get();

        return response()->json($data);
    }

    /**
     * Counter Leaderboard
     * Shows top performing users by parts counted and value
     */
    public function counterLeaderboard()
    {
        $data = DB::table($this->tableName)
            ->join('users', $this->tableName . '.user', '=', 'users.id')
            ->join('plants', 'users.plant', '=', 'plants.id')
            ->selectRaw("CONCAT(COALESCE(UPPER(users.initials), ''), ' ', COALESCE(plants.display_name, '')) as name")
            ->selectRaw('COUNT(*) as parts_counted')
            ->selectRaw('SUM(' . $this->tableName . '.cost_counted) as value_counted')
            ->selectRaw('SUM(ABS(' . $this->tableName . '.plus_minus)) as total_variance')
            ->selectRaw('ROUND(AVG(ABS(' . $this->tableName . '.plus_minus / NULLIF(' . $this->tableName . '.cost_expected, 0))) * 100, 1) as avg_variance_percent')
            ->selectRaw('COUNT(DISTINCT DATE(' . $this->tableName . '.date_counted)) as days_active')
            ->where($this->tableName . '.counted', 1)
            ->whereNotNull($this->tableName . '.user')
            ->groupBy('users.id', 'users.first_name', 'users.last_name')
            ->orderByDesc('parts_counted')
            ->limit(10)
            ->get();

        return response()->json($data);
    }
}
