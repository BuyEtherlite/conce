<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Licensing\Controllers\LicensingController;

/*
|--------------------------------------------------------------------------
| Licensing Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LicensingController::class, 'index'])->name('index');
Route::get('/dashboard', [LicensingController::class, 'dashboard'])->name('dashboard');

// Add your licensing specific routes here
