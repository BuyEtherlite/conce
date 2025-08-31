<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Engineering\Controllers\EngineeringController;

/*
|--------------------------------------------------------------------------
| Engineering Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [EngineeringController::class, 'index'])->name('index');
Route::get('/dashboard', [EngineeringController::class, 'dashboard'])->name('dashboard');

// Add your engineering specific routes here
