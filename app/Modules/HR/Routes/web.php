<?php

use Illuminate\Support\Facades\Route;
use App\Modules\HR\Controllers\HRController;

/*
|--------------------------------------------------------------------------
| HR Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HRController::class, 'index'])->name('index');
Route::get('/dashboard', [HRController::class, 'dashboard'])->name('dashboard');

// Add your hr specific routes here
