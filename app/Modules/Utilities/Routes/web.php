<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Utilities\Controllers\UtilitiesController;

/*
|--------------------------------------------------------------------------
| Utilities Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [UtilitiesController::class, 'index'])->name('index');
Route::get('/dashboard', [UtilitiesController::class, 'dashboard'])->name('dashboard');

// Add your utilities specific routes here
