<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('dashboard');
// });

// Ganti dengan controller
Route::get('/', [DashboardController::class, 'index']);
Route::resource('items', ItemController::class);