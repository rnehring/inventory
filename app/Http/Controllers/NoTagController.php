<?php

namespace App\Http\Controllers;

use App\Models\NoTagPart;
use App\Models\Inventory;
use App\Traits\UsesLocationTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoTagController extends FunctionController
{
    use UsesLocationTables;

    public $ntTableName;

    public function __construct()
    {
        parent::__construct();
        $this->ntTableName = $this->getTableName('notag');
        $this->tableName = $this->getTableName('inventory');
    }

    /**
     * Display the no-tag parts index
     */
    public function index()
    {
        $noTagParts = NoTagPart::with('counter')
            ->latest()
            ->get();

        return view('notag.index', [
            'plants' => $this->getWarehouses($this->tableName),
            'noTagParts' => $noTagParts,
            'bins' => json_encode($this->getAutocompleteBinsData()),
            'parts' => json_encode($this->getAutocompletePartsData()),
        ]);
    }

    /**
     * Show edit form for no-tag part
     */
    public function editNoTag(Request $request)
    {
        $noTagPart = NoTagPart::findOrFail($request->id);

        return view('notag.edit', [
            'plants' => $this->getWarehouses($this->tableName),
            'noTagPart' => $noTagPart,
        ]);
    }

    /**
     * Update no-tag part
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:no_tag_parts,id',
            'tag' => 'nullable|string|max:255',
            'part' => 'required|string|max:255',
            'bin' => 'required|string|max:50',
            'count' => 'required|numeric|min:0',
            'uom' => 'required|string|max:50',
            'by_weight' => 'nullable|boolean',
            'lot_number' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
        ]);

        $noTagPart = NoTagPart::findOrFail($validated['id']);
        
        // Get standard cost from inventory
        $standardCost = Inventory::where('part', $validated['part'])
            ->value('standard_cost') ?? 0;

        // Prepare update data
        $updateData = [
            'tag' => $validated['tag'],
            'part' => $validated['part'],
            'bin' => $validated['bin'],
            'count' => $validated['count'],
            'uom' => $validated['uom'],
            'by_weight' => $validated['by_weight'] ?? false,
            'lot_number' => $validated['lot_number'],
            'serial_number' => $validated['serial_number'],
            'standard_cost' => $standardCost,
            'cost_counted' => $standardCost * $validated['count'],
        ];

        $noTagPart->update($updateData);

        return redirect()
            ->route('notag.index')
            ->with('success', 'Part updated successfully');
    }

    /**
     * Save new no-tag part using validation and Eloquent
     */
    public function saveNoTagPart(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'part' => 'required|string|max:255',
            'bin' => 'required|string|max:50',
            'count' => 'required|numeric|min:0',
            'uom' => 'required|string|max:50',
            'by_weight' => 'nullable|boolean',
            'warehouse' => 'required|string|max:200',
            'lot_number' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
        ]);

        // Get standard cost from inventory
        $standardCost = Inventory::where('part', $validated['part'])
            ->value('standard_cost') ?? 0;

        $costCounted = $standardCost * $validated['count'];

        // Create new no-tag part
        $noTagPart = NoTagPart::create([
            'part' => $validated['part'],
            'bin' => $validated['bin'],
            'count' => $validated['count'],
            'uom' => $validated['uom'],
            'by_weight' => $validated['by_weight'] ?? false,
            'warehouse' => $validated['warehouse'],
            'lot_number' => $validated['lot_number'] ?? null,
            'serial_number' => $validated['serial_number'] ?? null,
            'user' => Auth::id(),
            'date_counted' => now()->format('Y-m-d'),
            'time_counted' => now()->format('H:i:s'),
            'standard_cost' => $standardCost,
            'cost_counted' => $costCounted,
        ]);

        // Load the counter relationship for response
        $noTagPart->load('counter');

        return response()->json($noTagPart);
    }
}
