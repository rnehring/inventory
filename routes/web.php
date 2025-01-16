<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/yesterdayCounts', [DashboardController::class, 'yesterdayCounts']);
Route::get('/allTimeCounts', [DashboardController::class, 'allTimeCounts']);
Route::get('/countsByCompany', [DashboardController::class, 'countsByCompany']);
Route::get('/percentageByCompany', [DashboardController::class, 'percentageByCompany']);
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);
