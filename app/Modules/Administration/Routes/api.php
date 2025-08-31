<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administration\Controllers\AdministrationApiController;

/*
|--------------------------------------------------------------------------
| Administration Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [AdministrationApiController::class, 'getStats'])->name('stats');

// Add your administration API routes here
