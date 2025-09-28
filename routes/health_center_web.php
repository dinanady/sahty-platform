<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\HealthCenter\DrugController;
use Illuminate\Support\Facades\Route;

Route::prefix('health-center')->name('health-center.')->group(function () {

    // Dashboard
    Route::get('/', [DrugController::class, 'index'])->name('dashboard');

    Route::resource('/drugs', DrugController::class)
        ->except(['edit']);

    Route::post('{id}/toggle', [DrugController::class, 'toggle'])->name('drugs.toggle');
    Route::get('available', [DrugController::class, 'available'])->name('drugs.available');

    Route::post('drugs/submit-new', [DrugController::class, 'submitNewDrug'])->name('drugs.submit-new');
    Route::get('/drugs-pending', [DrugController::class, 'pendingDrugs'])->name('drugs.pending');
    Route::get('/drugs-rejected', [DrugController::class, 'rejectedDrugs'])->name('drugs.rejected');
    Route::post('/drugs/{id}/resubmit', [DrugController::class, 'resubmit'])->name('drugs.resubmit');

    Route::resource('appointments', AppointmentController::class);
    Route::get('/appointments/create/modal', [AppointmentController::class, 'createModal'])->name('appointments.create.modal');
    Route::post('/get-child-by-national-id', [AppointmentController::class, 'getChildByNationalId'])->name('get.child.by.national.id');
});
