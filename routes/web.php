<?php

use App\Http\Controllers\CountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NoTagController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

Route::get('/data', [DataController::class, 'index']);

Route::get('/count', [CountController::class, 'index']);
Route::post('/inventory-search', [CountController::class, 'getPart'] );
Route::post('/update-count', [CountController::class, 'updateCount'] );

Route::get('/location', [LocationController::class, 'index']);
Route::post('/location-search', [LocationController::class, 'getPartsByLocation']);

Route::get('/notag', [NoTagController::class, 'index']);
Route::post('/notag/save', [NoTagController::class, 'saveNoTagPart']);
