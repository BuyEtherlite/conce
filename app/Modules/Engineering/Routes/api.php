<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Engineering\Controllers\EngineeringApiController;

/*
|--------------------------------------------------------------------------
| Engineering Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [EngineeringApiController::class, 'getStats'])->name('stats');

// Add your engineering API routes here
