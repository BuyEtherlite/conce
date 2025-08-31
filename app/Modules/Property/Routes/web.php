<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Property\Controllers\PropertyController;

/*
|--------------------------------------------------------------------------
| Property Module Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])->prefix('property')->name('property.')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('index');
    Route::get('/dashboard', [PropertyController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [PropertyController::class, 'create'])->name('create');
    Route::post('/', [PropertyController::class, 'store'])->name('store');
    Route::get('/{property}', [PropertyController::class, 'show'])->name('show');
    Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
    Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
    
    // Specialized views
    Route::get('/land-records', [PropertyController::class, 'landRecords'])->name('land-records');
    Route::get('/valuations', [PropertyController::class, 'valuations'])->name('valuations');
    Route::get('/leases', [PropertyController::class, 'leases'])->name('leases');
});
