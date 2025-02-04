<?php

use App\Http\Controllers\CountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);


Route::get('/data', [DataController::class, 'index']);
Route::get('/count', [CountController::class, 'index']);
