<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\Inventory;
use App\Models\NoTagPart;
use App\Models\PartUom;
use App\Models\PreCount;
use App\Traits\UsesLocationTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FunctionController extends Controller
{
    use UsesLocationTables;

    public $tableName;
    public $tableNamePre;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = $this->getTableName('inventory');
        $this->tableNamePre = $this->getTableName('precount');
    }

    /**
     * Update inventory count using Eloquent
     */
    public function updateCount(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'part' => 'required|exists:inventory,id',
            'count' => 'required|numeric|min:0',
            'byweight' => 'boolean',
        ]);

        $inventory = Inventory::findOrFail($validated['part']);

        $inventory->update([
            'count' => $validated['count'],
            'user' => Auth::id(),
            'by_weight' => $validated['byweight'],
            'tag_printed' => true,
            'counted' => true,
        ]);

        // Refresh to get updated calculated values from triggers
        $inventory->refresh();

        return response()->json([
            [
                'cost_counted' => $inventory->cost_counted,
                'plus_minus' => $inventory->plus_minus,
                'tag_printed' => $inventory->tag_printed,
            ]
        ]);
    }

    /**
     * Update precount using Eloquent
     */
    public function updatePreCount(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'part' => 'required|exists:inventory_precount,id',
            'count' => 'required|numeric|min:0',
            'bin_verified' => 'nullable|boolean',
        ]);

        $precount = PreCount::findOrFail($validated['part']);

        $precount->update([
            'count' => $validated['count'],
            'bin_verified' => $validated['bin_verified'] ?? 0,
            'user' => Auth::id(),
            'tag_status' => true,
            'counted' => true,
        ]);

        // Refresh to get updated calculated values from triggers
        $precount->refresh();

        return response()->json([
            [
                'cost_counted' => $precount->cost_counted,
                'plus_minus' => $precount->plus_minus,
                'tag_status' => $precount->tag_status,
            ]
        ]);
    }

    /**
     * Get autocomplete bins data (for views)
     */
    protected function getAutocompleteBinsData(): array
    {
        $warehouse = session('plant');

        return Cache::remember("autocomplete_bins_{$warehouse}", 3600, function() use ($warehouse) {
            return Bin::where('warehouse', $warehouse)
                ->pluck('bin')
                ->toArray();
        });
    }

    /**
     * Get autocomplete bins with caching (API endpoint)
     */
    public function getAutocompleteBins()
    {
        return response()->json($this->getAutocompleteBinsData());
    }

    /**
     * Get autocomplete parts data (for views)
     */
    protected function getAutocompletePartsData(): array
    {
        $warehouse = session('plant');

        return Cache::remember("autocomplete_parts_{$warehouse}", 3600, function() use ($warehouse) {
            return Inventory::where('warehouse', $warehouse)
                ->distinct()
                ->pluck('part')
                ->toArray();
        });
    }

    /**
     * Get autocomplete parts with caching (API endpoint)
     */
    public function getAutocompleteParts()
    {
        return response()->json($this->getAutocompletePartsData());
    }

    /**
     * Get part UOM using Eloquent
     */
    public function getPartUom(Request $request)
    {
        $request->validate([
            'part' => 'required|string|max:255',
        ]);

        $uom = DB::table('part_uom')
            ->where('part', $request->part)
            ->value('uom');

        return response()->json(['uom' => $uom]);
    }

    /**
     * Get warehouses using Eloquent
     */
    public function getWarehouses(string $table): array
    {
        return Cache::remember('warehouses_active', 3600, function() {
            return DB::table('plants')
                ->where('active', 1)
                ->orderBy('plant')
                ->get(['id', 'plant', 'display_name'])
                ->toArray();
        });
    }

    /**
     * Get part numbers with caching
     */
    public function getPartNumbers()
    {
        $warehouse = session('plant');

        $partNumbers = Cache::remember("part_numbers_{$warehouse}", 3600, function() use ($warehouse) {
            return Inventory::where('warehouse', $warehouse)
                ->distinct()
                ->pluck('part')
                ->toArray();
        });

        return response()->json($partNumbers);
    }

    public function getAllPossibleAutocompleteParts()
    {
        return response()->json($this->getAllPossiblePartNumbers());
    }

    /**
     * Get part numbers with caching
     */
    public function getAllPossiblePartNumbers(): array
    {
        return Cache::remember("all_possible_part_numbers", 3600, function() {
            return PartUom::pluck('part')
                ->toArray();
        });
    }

    /**
     * Get all uom values with caching
     */
    public function getAllPossiblePartUoms(): array
    {
        return Cache::remember("all_possible_part_uoms", 3600, function() {
            return PartUom::groupBy('uom')
                ->pluck('uom', 'uom')
                ->toArray();
        });
    }

    public static function getPlantNameFromCode(string $plantCode){
        return match($plantCode){
            'P1-RAW' => 'Plant 1',
            'P2-RAW' => 'Plan 2',
            'P3-RAW' => 'Plan 3',
            'P4-RAW' => 'Plan 4',
            default => 'Plant 1',
        };
    }

    /**
     * Get part UOM using Eloquent
     */
    public function checkTracking(Request $request)
    {
        $request->validate([
            'part' => 'required|string|max:255',
        ]);

        return DB::table('part_uom')
            ->where('part', "=", $request->part)
            ->get(['track_serial', 'track_lot'])
            ->toArray();
    }

    public function checkSerial(Request $request)
    {
        if ( $request->serial == "" ){
            return json_encode(["false"]);
        }

        $request->validate([
            'serial' => 'string|max:255',
        ]);

        $exists = Inventory::where('serial_number', $request->serial)->exists()
            || NoTagPart::where('serial_number', $request->serial)->exists();

        if(!$exists){
            return json_encode(["false"]);
        } else{
            return json_encode(["true"]);
        }
    }

    public function getAllActivePlantsForFilters()
    {
        return response()->json($this->getAllActivePlants());
    }

    /**
     * Get part numbers with caching
     */
    public function getAllActivePlants(): array
    {
        return Cache::remember("all_active_plants", 3600, function() {
            return DB::table('plants')
            ->select('plant','display_name')
                ->where('active', 1)
                ->get()
                ->toArray();
        });
    }

    public function getAllUsersForFilters()
    {
        return response()->json($this->getAllUsers());
    }

    /**
     * Get all users for filters with caching
     */
    public function getAllUsers(): array
    {
        return Cache::remember("users_for_filters", 3600, function() {
            return DB::table('users')
                ->join('plants', 'users.plant', '=', 'plants.id')
                ->select('users.id', 'users.initials', 'users.first_name', 'users.last_name', 'plants.plant as plant')
                ->where('plants.active', 1)
                ->orderBy('users.initials')
                ->get()
                ->toArray();
        });
    }
}
