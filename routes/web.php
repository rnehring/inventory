<?php

use App\Http\Controllers\CountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoTagController;
use App\Http\Controllers\PreCountController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');

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

// DASHBOARD ROUTES
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

// DATA ROUTES
Route::get('/data', [DataController::class, 'index']);

// COUNT ROUTES
Route::get('/count', [CountController::class, 'index']);
Route::post('/inventory-search', [CountController::class, 'getPart'] );
Route::post('/update-count', [CountController::class, 'updateCount'] );

// PRECOUNT ROUTES
Route::get('/pre-count', [PreCountController::class, 'index']);
//Route::post('/inventory-search', [PreCountController::class, 'getPart'] );
//Route::post('/update-count', [PreCountController::class, 'updateCount'] );

// LOCATION ROUTES
Route::get('/location', [LocationController::class, 'index']);
Route::post('/location-search', [LocationController::class, 'getPartsByLocation']);

// NO TAG ROUTES
Route::get('/notag', [NoTagController::class, 'index']);
Route::post('/notag/save', [NoTagController::class, 'saveNoTagPart']);

// CSV ROUTES
Route::post('/download-data', [DataController::class, 'downloadData']);

