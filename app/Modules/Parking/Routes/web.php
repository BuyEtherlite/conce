<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Parking\Controllers\ParkingController;

/*
|--------------------------------------------------------------------------
| Parking Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ParkingController::class, 'index'])->name('index');
Route::get('/dashboard', [ParkingController::class, 'dashboard'])->name('dashboard');

// Add your parking specific routes here
