<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Housing\Controllers\HousingController;

/*
|--------------------------------------------------------------------------
| Housing Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HousingController::class, 'index'])->name('index');
Route::get('/dashboard', [HousingController::class, 'dashboard'])->name('dashboard');

// Add your housing specific routes here
