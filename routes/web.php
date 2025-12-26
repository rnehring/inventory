<?php

use App\Http\Controllers\CountController;
use App\Http\Controllers\DashboardControllerOld;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocationPreController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoTagController;
use App\Http\Controllers\PreCountController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index']);

// ADMIN ROUTES
Route::view('/admin', 'admin');
Route::post('/create-manager', [LoginController::class, 'createManager']);

// LOGIN ROUTES
Route::get('/employee-login', [LoginController::class, 'employeeLogin']);
Route::post('/employee-login', [LoginController::class, 'loginEmployee']);
Route::get('/manager-login', [LoginController::class, 'managerLogin']);
Route::post('/manager-login', [LoginController::class, 'loginManager']);

// SESSION ROUTES
Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);


Route::middleware(['auth'])->group(function () {

    // Main dashboard with Expected Qty Reliability
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Detailed drill-down pages
    Route::get('/dashboard/surprise-finds', [DashboardController::class, 'surpriseFinds'])
        ->name('dashboard.surprise-finds');

    Route::get('/dashboard/missing-stock', [DashboardController::class, 'missingStock'])
        ->name('dashboard.missing-stock');

    // API endpoint for AJAX data refresh (optional)
    Route::get('/api/dashboard/data', [DashboardController::class, 'getData'])
        ->name('api.dashboard.data');

    Route::get('/all-time-counts', [DashboardController::class, 'allTimeCounts'])
        ->name('dashboard.allTimeCounts');

    Route::get('/brand-progress', [DashboardController::class, 'percentageByCompany'])
        ->name('dashboard.brandProgress');

    Route::get('/warehouse-value', [DashboardController::class, 'warehouseValue'])
        ->name('dashboard.warehouseValue');

    // DATA ROUTES
    Route::get('/data', [DataController::class, 'index']);
    Route::post('/company-data', [DataController::class, 'currentData']);
    Route::get('/company-data', [DataController::class, 'currentData']);
    Route::get('/get-all-data', [DataController::class, 'getAllData']);

    // USER ROUTES
    Route::get('/users', [UserController::class, 'index'])->name('users.index');;
    Route::get('/users/edit/{id}', [UserController::class, 'editUser']);
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/new', [UserController::class, 'newUser'])->name('users.new');
    Route::post('/users/new', [UserController::class, 'new'])->name('users.add');;
    Route::get('/users/delete/{id}', [UserController::class, 'deleteUser']);
});



// COUNT ROUTES
Route::get('/count', [CountController::class, 'index']);
Route::post('/inventory-search', [CountController::class, 'getPart'] );
Route::post('/update-count', [FunctionController::class, 'updateCount'] );

// PRECOUNT ROUTES
Route::get('/pre-count', [PreCountController::class, 'index']);
Route::post('/inventory-precount-search', [PreCountController::class, 'getPart'] );
Route::post('/update-precount', [FunctionController::class, 'updatePreCount'] );

// LOCATION ROUTES
Route::get('/location', [LocationController::class, 'index']);
Route::post('/location-search', [LocationController::class, 'getPartsByLocation']);

// LOCATION PRECOUNT ROUTES
Route::get('/locationpre', [LocationPreController::class, 'index']);
Route::post('/location-precount-search', [LocationPreController::class, 'getPartsByLocation']);

// NO TAG ROUTES
Route::get('/notag', [NoTagController::class, 'index'])->name('notag.index');
Route::get('/notag/edit/{id}', [NoTagController::class, 'editNoTag']);
Route::post('/notag/save', [NoTagController::class, 'saveNoTagPart']);
Route::post('/notag/update', [NoTagController::class, 'update'])->name('notag.update');

// CSV ROUTES
Route::post('/download-data', [DataController::class, 'downloadData']);
Route::get('/upload', [UploadController::class, 'index']);

// UPLOAD INVENTORY ROUTES
Route::post('/upload', [UploadController::class, 'processUpload']);
Route::get('/review-upload', [UploadController::class, 'reviewUpload']);
Route::post('/save-upload', [UploadController::class, 'saveUpload']);

//UPLOAD PRECOUNT ROUTES
Route::post('/upload-precount', [UploadController::class, 'processPrecountUpload']);
Route::get('/review-precount', [UploadController::class, 'reviewPrecountUpload']);
Route::post('/save-precount-upload', [UploadController::class, 'savePrecountUpload']);



Route::get('/get-uploaded-precount-data', [UploadController::class, 'getUploadedPrecountDataForReview']);
Route::get('/get-uploaded-data', [UploadController::class, 'getUploadedDataForReview']);
