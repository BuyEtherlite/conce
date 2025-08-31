<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Parking\Controllers\ParkingApiController;

/*
|--------------------------------------------------------------------------
| Parking Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [ParkingApiController::class, 'getStats'])->name('stats');

// Add your parking API routes here
