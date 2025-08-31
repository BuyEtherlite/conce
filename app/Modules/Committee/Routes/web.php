<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Committee\Controllers\CommitteeController;

/*
|--------------------------------------------------------------------------
| Committee Module Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('committee')->name('committee.')->group(function () {
    Route::get('/', [CommitteeController::class, 'index'])->name('index');

    // Committee management routes
    Route::prefix('committees')->name('committees.')->group(function () {
        Route::get('/', [CommitteeController::class, 'index'])->name('index');
        Route::get('/create', [CommitteeController::class, 'create'])->name('create');
        Route::post('/', [CommitteeController::class, 'store'])->name('store');
        Route::get('/{committee}', [CommitteeController::class, 'show'])->name('show');
        Route::get('/{committee}/edit', [CommitteeController::class, 'edit'])->name('edit');
        Route::put('/{committee}', [CommitteeController::class, 'update'])->name('update');
        Route::delete('/{committee}', [CommitteeController::class, 'destroy'])->name('destroy');
    });
});