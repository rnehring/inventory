<?php

namespace App\Http\Controllers;
use App\Jobs\ProcessInventoryImport;
use App\Jobs\ProcessPrecountInventoryImport;
use App\Models\InventoryUploadPrecount;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;
use App\Models\InventoryUpload;

class UploadController extends FunctionController
{

    public $tableName;
    public ProcessPrecountInventoryImport $precountProcess;
    public ProcessInventoryImport $countProcess;

    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
            $this->preCountTableName = "inventory_precount";
            $this->preCountUploadTableName = "inventory_precount_upload";
        }
        else{
            $this->tableName = "inventory_houston";
        }
    }

    public function index(){
        return view('upload.index');
    }

    // PRECOUNT UPLOAD FUNCTIONS
    //__________________________________________________________________________________________________________
    public function getUploadedPrecountDataForReview(Request $request) {
        $uploadedPrecountData = DB::select('
            SELECT * FROM inventory_precount_upload');

        $uploadedPrecountData = json_decode(json_encode($uploadedPrecountData), true);
        return json_encode($uploadedPrecountData);
    }

    public function processPrecountUpload(Request $request){
        InventoryUploadPrecount::query()->delete();
        $mapping = config('precount_csv_mappings.inventory_precount');
        $path = $request->file('precount-csv')->getRealPath();
        $preCountProcessor = new ProcessPrecountInventoryImport($path, $mapping);
        if( $preCountProcessor->handle() ){
            return redirect('/review-precount');
        }
        return false;
    }

    public function reviewPrecountUpload(Request $request){
        $totalParts = count(InventoryUploadPrecount::all());
        return view('upload.review-precount',
            [
                'totalParts' => $totalParts
            ]);
    }

    public function savePrecountUpload(Request $request){
        DB::statement('SET @disable_top_eighty_calc = 1');
        DB::statement("CALL backup_inventory_precount_upload('" . $this->preCountTableName . "');");
        DB::statement("TRUNCATE TABLE " . $this->preCountTableName . ";");
        DB::statement("CALL copy_upload_to_inventory_precount('" . $this->preCountTableName . "');");
        DB::statement("CALL update_top_eighty_precount('inventory_precount');");
        return view('upload.precount-saved');
    }

    // INVENTORY UPLOAD FUNCTIONS
    // ________________________________________________________________________________________________________
    public function getUploadedDataForReview(Request $request) {
        $uploadedData = DB::select('
            SELECT * FROM inventory_upload');

        $uploadedData = json_decode(json_encode($uploadedData), true);
        return json_encode($uploadedData);
    }

    public function processUpload(Request $request){
        // Validate the upload
        $request->validate([
            'upload-inventory-csv' => 'required|file|mimes:csv,txt|max:10240' // 10MB max
        ]);
        
        // Check if file exists
        if (!$request->hasFile('upload-inventory-csv')) {
            return back()->withErrors(['upload-inventory-csv' => 'No file was uploaded']);
        }
        
        // Check if file is valid
        if (!$request->file('upload-inventory-csv')->isValid()) {
            return back()->withErrors(['upload-inventory-csv' => 'The uploaded file is invalid']);
        }
        
        InventoryUpload::query()->delete();
        $mapping = config('csv_mappings.inventory');
        $path = $request->file('upload-inventory-csv')->getRealPath();
        $countProcessor = new ProcessInventoryImport($path, $mapping);
        if( $countProcessor->handle() ){
            return redirect('/review-upload');
        }
        return back()->withErrors(['upload-inventory-csv' => 'Failed to process the CSV file']);
    }

    public function reviewUpload(Request $request){
        $totalParts = count(InventoryUpload::all());
        return view('upload.review-upload',
            [
                'totalParts' => $totalParts
            ]);
    }

    public function saveUpload(Request $request){
        DB::statement('SET @disable_top_eighty_calc = 1');
        DB::statement("CALL backup_inventory_upload()");
        DB::statement("TRUNCATE TABLE " . $this->tableName . ";");
        DB::statement("CALL copy_upload_to_inventory('" . $this->tableName . "');");
        DB::statement("CALL update_top_eighty('inventory');");
        return view('upload.upload-saved');
    }











}
