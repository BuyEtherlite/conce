<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Water\Controllers\WaterController;

/*
|--------------------------------------------------------------------------
| Water Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [WaterController::class, 'index'])->name('index');
Route::get('/dashboard', [WaterController::class, 'dashboard'])->name('dashboard');

// Add your water specific routes here
