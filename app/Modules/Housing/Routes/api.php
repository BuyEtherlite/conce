<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Housing\Controllers\HousingApiController;

/*
|--------------------------------------------------------------------------
| Housing Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [HousingApiController::class, 'getStats'])->name('stats');

// Add your housing API routes here
