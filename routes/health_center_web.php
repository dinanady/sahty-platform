<?php
use App\Http\Controllers\HealthCenter\DrugController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'health.center'])->prefix('health-center')->name('health-center.')->group(function () {

    // Dashboard
    Route::get('/', [DrugController::class, 'index'])->name('dashboard');

    Route::prefix('drugs')->name('drugs.')->group(function () {
        Route::resource('', DrugController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::post('{id}/toggle', [DrugController::class, 'toggle'])->name('toggle');
        Route::get('available', [DrugController::class, 'available'])->name('available');

        // ========== Add new drug ==========
        Route::get('create', [DrugController::class, 'create'])->name('create');
        Route::post('submit-new', [DrugController::class, 'submitNewDrug'])->name('submit-new');
        Route::get('pending', [DrugController::class, 'pendingDrugs'])->name('pending');
        Route::get('rejected', [DrugController::class, 'rejectedDrugs'])->name('rejected');
        Route::post('{id}/resubmit', [DrugController::class, 'resubmit'])->name('resubmit');
    });

});
