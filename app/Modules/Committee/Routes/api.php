<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Committee\Controllers\CommitteeApiController;

/*
|--------------------------------------------------------------------------
| Committee Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [CommitteeApiController::class, 'getStats'])->name('stats');

// Add your committee API routes here
