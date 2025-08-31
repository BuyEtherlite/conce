<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Water\Controllers\WaterApiController;

/*
|--------------------------------------------------------------------------
| Water Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [WaterApiController::class, 'getStats'])->name('stats');

// Add your water API routes here
