<?php

use App\Http\Controllers\CountController;
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);

    Route::get('/employee-login', [LoginController::class, 'employeeLogin'])->name('employee.login');
    Route::post('/employee-login', [LoginController::class, 'loginEmployee']);

    Route::get('/manager-login', [LoginController::class, 'managerLogin'])->name('manager.login');
    Route::post('/manager-login', [LoginController::class, 'loginManager']);
});

// Admin Routes (unprotected for initial setup)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin')->name('index');
    Route::post('/create-manager', [LoginController::class, 'createManager'])->name('create-manager');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Employee & Manager Routes (All Authenticated Users)
    |--------------------------------------------------------------------------
    */

    // Inventory Counting
    Route::prefix('count')->name('count.')->group(function () {
        Route::get('/', [CountController::class, 'index'])->name('index');
        Route::post('/search', [CountController::class, 'getPart'])->name('search');
        Route::post('/update', [FunctionController::class, 'updateCount'])->name('update');
    });

    // Pre-Counting
    Route::prefix('pre-count')->name('precount.')->group(function () {
        Route::get('/', [PreCountController::class, 'index'])->name('index');
        Route::post('/search', [PreCountController::class, 'getPart'])->name('search');
        Route::post('/update', [FunctionController::class, 'updatePreCount'])->name('update');
    });

    // Location Counting
    Route::prefix('location')->name('location.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::post('/search', [LocationController::class, 'getPartsByLocation'])->name('search');
    });

    // Location Pre-Counting
    Route::prefix('locationpre')->name('locationpre.')->group(function () {
        Route::get('/', [LocationPreController::class, 'index'])->name('index');
        Route::post('/search', [LocationPreController::class, 'getPartsByLocation'])->name('search');
    });

    // No Tag Parts
    Route::prefix('notag')->name('notag.')->group(function () {
        Route::get('/', [NoTagController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [NoTagController::class, 'editNoTag'])->name('edit');
        Route::post('/save', [NoTagController::class, 'saveNoTagPart'])->name('save');
        Route::post('/update', [NoTagController::class, 'update'])->name('update');
    });

    // Shared API endpoints
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/part-numbers', [FunctionController::class, 'getPartNumbers'])->name('part-numbers');
        Route::get('/bins', [FunctionController::class, 'getAutocompleteBins'])->name('bins');
        Route::post('/part-uom', [FunctionController::class, 'getPartUom'])->name('part-uom');

    });

    /*
    |--------------------------------------------------------------------------
    | Manager-Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('manager')->group(function () {

        // Dashboard
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::get('/surprise-finds', [DashboardController::class, 'surpriseFinds'])->name('surprise-finds');
            Route::get('/missing-stock', [DashboardController::class, 'missingStock'])->name('missing-stock');
            Route::get('/all-time-counts', [DashboardController::class, 'allTimeCounts'])->name('all-time-counts');
            Route::get('/brand-progress', [DashboardController::class, 'percentageByCompany'])->name('brand-progress');
            Route::get('/warehouse-value', [DashboardController::class, 'warehouseValue'])->name('warehouse-value');

            // New charts
            Route::get('/abc-analysis', [DashboardController::class, 'abcAnalysis'])->name('abc-analysis');
            Route::get('/variance-distribution', [DashboardController::class, 'varianceDistribution'])->name('variance-distribution');
            Route::get('/count-velocity', [DashboardController::class, 'countVelocity'])->name('count-velocity');
            Route::get('/top-bins', [DashboardController::class, 'topBinsByValue'])->name('top-bins');
            Route::get('/warehouse-progress', [DashboardController::class, 'warehouseProgress'])->name('warehouse-progress');
            Route::get('/counter-leaderboard', [DashboardController::class, 'counterLeaderboard'])->name('counter-leaderboard');

            // API endpoint for AJAX data refresh
            Route::get('/data', [DashboardController::class, 'getData'])->name('data');
        });

        // Data Management
        Route::prefix('data')->name('data.')->group(function () {
            Route::get('/', [DataController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/company-data', [DataController::class, 'currentData'])->name('company-data');
            Route::get('/all', [DataController::class, 'getAllData'])->name('all');
            Route::post('/download', [DataController::class, 'downloadData'])->name('download');
            Route::get('/export-inventory', [DataController::class, 'exportInventory'])->name('export-inventory');
            Route::get('/export-notag', [DataController::class, 'exportNoTagData'])->name('export-notag');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/new', [UserController::class, 'newUser'])->name('new');
            Route::post('/new', [UserController::class, 'new'])->name('store');
            Route::get('/edit/{id}', [UserController::class, 'editUser'])->name('edit');
            Route::post('/update', [UserController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [UserController::class, 'deleteUser'])->name('delete');
        });

        // File Uploads
        Route::prefix('upload')->name('upload.')->group(function () {
            Route::get('/', [UploadController::class, 'index'])->name('index');

            // Inventory Upload
            Route::post('/inventory', [UploadController::class, 'processUpload'])->name('inventory.process');
            Route::get('/inventory/review', [UploadController::class, 'reviewUpload'])->name('inventory.review');
            Route::post('/inventory/save', [UploadController::class, 'saveUpload'])->name('inventory.save');
            Route::get('/inventory/data', [UploadController::class, 'getUploadedDataForReview'])->name('inventory.data');

            // Pre-count Upload
            Route::post('/precount', [UploadController::class, 'processPrecountUpload'])->name('precount.process');
            Route::get('/precount/review', [UploadController::class, 'reviewPrecountUpload'])->name('precount.review');
            Route::post('/precount/save', [UploadController::class, 'savePrecountUpload'])->name('precount.save');
            Route::get('/precount/data', [UploadController::class, 'getUploadedPrecountDataForReview'])->name('precount.data');
        });
    });
});

// Legacy route support - these maintain backward compatibility
// TODO: Update frontend to use new route names, then remove these
Route::middleware('auth')->group(function () {
    Route::post('/inventory-search', [CountController::class, 'getPart']);
    Route::post('/update-count', [FunctionController::class, 'updateCount']);
    Route::post('/inventory-precount-search', [PreCountController::class, 'getPart']);
    Route::post('/update-precount', [FunctionController::class, 'updatePreCount']);
    Route::post('/location-search', [LocationController::class, 'getPartsByLocation']);
    Route::post('/location-precount-search', [LocationPreController::class, 'getPartsByLocation']);
    Route::get('/get-part-numbers', [FunctionController::class, 'getPartNumbers']);
    Route::get('/get-bins', [FunctionController::class, 'getAutocompleteBins']);
    Route::post('/get-part-uom', [FunctionController::class, 'getPartUom']);
    Route::post('/check-tracking', [FunctionController::class, 'checkTracking']);
    Route::post('/check-serial', [FunctionController::class, 'checkSerial']);
    Route::get('/get-plants', [FunctionController::class, 'getWarehouses']);
});

Route::middleware(['auth', 'manager'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/all-time-counts', [DashboardController::class, 'allTimeCounts']);
    Route::get('/brand-progress', [DashboardController::class, 'percentageByCompany']);
    Route::get('/warehouse-value', [DashboardController::class, 'warehouseValue']);

    // New chart endpoints
    Route::get('/abc-analysis', [DashboardController::class, 'abcAnalysis']);
    Route::get('/variance-distribution', [DashboardController::class, 'varianceDistribution']);
    Route::get('/count-velocity', [DashboardController::class, 'countVelocity']);
    Route::get('/top-bins', [DashboardController::class, 'topBinsByValue']);
    Route::get('/warehouse-progress', [DashboardController::class, 'warehouseProgress']);
    Route::get('/counter-leaderboard', [DashboardController::class, 'counterLeaderboard']);

    Route::get('/data', [DataController::class, 'index']);
    Route::get('/get-all-data', [DataController::class, 'getAllData']);
    Route::post('/download-data', [DataController::class, 'downloadData']);
    Route::post('/upload', [UploadController::class, 'processUpload']);
    Route::get('/review-upload', [UploadController::class, 'reviewUpload']);
    Route::post('/save-upload', [UploadController::class, 'saveUpload']);
    Route::post('/upload-precount', [UploadController::class, 'processPrecountUpload']);
    Route::get('/review-precount', [UploadController::class, 'reviewPrecountUpload']);
    Route::post('/save-precount-upload', [UploadController::class, 'savePrecountUpload']);
    Route::get('/get-uploaded-precount-data', [UploadController::class, 'getUploadedPrecountDataForReview']);
    Route::get('/get-uploaded-data', [UploadController::class, 'getUploadedDataForReview']);
});
