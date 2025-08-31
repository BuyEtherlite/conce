<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Health\Controllers\HealthController;

/*
|--------------------------------------------------------------------------
| Health Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HealthController::class, 'index'])->name('index');
Route::get('/dashboard', [HealthController::class, 'dashboard'])->name('dashboard');

// Add your health specific routes here
