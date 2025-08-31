<?php

use Illuminate\Support\Facades\Route;
use App\Modules\HR\Controllers\HRApiController;

/*
|--------------------------------------------------------------------------
| HR Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [HRApiController::class, 'getStats'])->name('stats');

// Add your hr API routes here
