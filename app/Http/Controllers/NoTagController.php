<?php

namespace App\Http\Controllers;

use App\Models\NoTagPart;
use App\Models\PartCost;
use App\Traits\UsesLocationTables;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            ->limit(100)
            ->get();

        return view('notag.index', [
            'plants' => $this->getWarehouses($this->tableName),
            'noTagParts' => $noTagParts,
            'bins' => json_encode($this->getAutocompleteBinsData()),
            'parts' => json_encode($this->getAllPossiblePartNumbers()),
            'uoms' => $this->getAllPossiblePartUoms(),
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

    public function editAll(Request $request)
    {
        $noTagParts = NoTagPart::with('counter')
            ->latest()
            ->get();

        return view('notag.edit-all', [
            'noTagParts' => $noTagParts,
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
            'by_weight' => 'nullable|boolean',
            'lot_number' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
        ]);

        $noTagPart = NoTagPart::findOrFail($validated['id']);

        // Get standard cost from part_price table
        $standardCost = PartCost::where('part', $validated['part'])
            ->value('price') ?? 0;

        // Prepare update data
        $updateData = [
            'tag' => $validated['tag'],
            'part' => $validated['part'],
            'bin' => $validated['bin'],
            'count' => $validated['count'],
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

        // Get standard cost from part_price table
        $standardCost = PartCost::where('part', $validated['part'])
            ->value('price') ?? 0;

        $costCounted = $standardCost * $validated['count'];

        $partExists = $this->checkIfNoTagPartExists($validated);

        if ($partExists == false) {
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
        } else {
            $error = [];
            $error['type'] = 'Part Exists';
            $error['message'] = 'This No Tag Part already exists. Record not saved.';

            throw new HttpResponseException(
                response()->json($error, 400)
            );
        }
    }

    function checkIfNoTagPartExists($partDetails){
        if($partDetails['lot_number'] != null && $partDetails['lot_number'] != ''){
            $lot_number = true;
        } else {
            $lot_number = false;
        }
        if($partDetails['serial_number'] != null && $partDetails['serial_number'] != ''){
            $serial_number = true;
            if ( FunctionController::checkSerial($partDetails['serial_number']) ){
                $error = [];
                $error['type'] = 'Serial Exists';
                $error['message'] = 'This Serial Number already exists. Record not saved.';
                throw new HttpResponseException(
                    response()->json($error, 400)
                );
            }
        } else {
            $serial_number = false;
        }

        if( $lot_number && $serial_number ){
            $exists = DB::table('no_tag_parts')
                ->where('part', $partDetails['part'])
                ->where('bin', $partDetails['bin'])
                ->where('lot_number', $partDetails['lot_number'])
                ->where('serial_number', $partDetails['serial_number'])
                ->get();
        } else if( $lot_number ){
            $exists = DB::table('no_tag_parts')
                ->where('part', $partDetails['part'])
                ->where('bin', $partDetails['bin'])
                ->where('lot_number', $partDetails['lot_number'])
                ->get();
        } else if( $serial_number ){
            $exists = DB::table('no_tag_parts')
                ->where('part', $partDetails['part'])
                ->where('bin', $partDetails['bin'])
                ->where('serial_number', $partDetails['serial_number'])
                ->get();
        } else {
            $exists = DB::table('no_tag_parts')
                ->where('part', $partDetails['part'])
                ->where('bin', $partDetails['bin'])
                ->get();
        }

        if($exists->isEmpty()){
            return false;
        }
        return true;
    }

    public function deleteNoTag(Request $request){
        $noTag = NoTagPart::findOrFail($request->id);
        $noTag->delete();

        // Return JSON instead of redirect
        return response()->json(['success' => true, 'message' => 'Part deleted successfully']);
    }
}
