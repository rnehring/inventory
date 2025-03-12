<?php

use App\Http\Controllers\CountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoTagController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');

Route::view('/admin', 'admin');
Route::post('/create-manager', [LoginController::class, 'createManager']);

Route::get('/employee-login', [LoginController::class, 'employeeLogin']);
Route::post('/employee-login', [LoginController::class, 'loginEmployee']);
Route::get('/manager-login', [LoginController::class, 'managerLogin']);
Route::post('/manager-login', [LoginController::class, 'loginManager']);

Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

Route::get('/data', [DataController::class, 'index']);

Route::get('/count', [CountController::class, 'index']);
Route::post('/inventory-search', [CountController::class, 'getPart'] );
Route::post('/update-count', [CountController::class, 'updateCount'] );

Route::get('/location', [LocationController::class, 'index']);
Route::post('/location-search', [LocationController::class, 'getPartsByLocation']);

Route::get('/notag', [NoTagController::class, 'index']);
Route::post('/notag/save', [NoTagController::class, 'saveNoTagPart']);



