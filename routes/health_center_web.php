<?php

use App\Http\Controllers\HealthCenter\AppointmentController;
use App\Http\Controllers\HealthCenter\DashboardController;
use App\Http\Controllers\HealthCenter\DoctorController;
use App\Http\Controllers\HealthCenter\DrugController;
use App\Http\Controllers\HealthCenter\VaccineController;
use App\Http\Controllers\HealthCenter\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'health.center'])->prefix('health-center')->name('health-center.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    //drugs
    Route::resource('/drugs', DrugController::class)
        ->except(['edit']);
    Route::post('drugs/{id}/toggle', [DrugController::class, 'toggle'])->name('drugs.toggle');
    Route::get('drugs-available', [DrugController::class, 'available'])->name('drugs.available');
    Route::post('drugs-submit-new', [DrugController::class, 'submitNewDrug'])->name('drugs.submit-new');
    Route::get('/drugs-pending', [DrugController::class, 'pendingDrugs'])->name('drugs.pending');
    Route::get('/drugs-rejected', [DrugController::class, 'rejectedDrugs'])->name('drugs.rejected');
    Route::post('/drugs/{id}/resubmit', [DrugController::class, 'resubmit'])->name('drugs.resubmit');


    //vaccines
    Route::resource('vaccines', VaccineController::class);
    Route::put('vaccines/{id}/availability', [VaccineController::class, 'updateAvailability'])->name('vaccines.availability');

    //vaccine appointments
    Route::resource('appointments', AppointmentController::class);
    Route::get('/appointments/create/modal', [AppointmentController::class, 'createModal'])->name('appointments.create.modal');
    Route::post('/get-child-by-national-id', [AppointmentController::class, 'getChildByNationalId'])->name('get.child.by.national.id');

    Route::resource('users', UserController::class);
    // Doctor
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::post('doctors', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('doctors/{id}/show-details', [DoctorController::class, 'showDetails'])->name('doctors.show.details');
    Route::get('doctors/{id}/edit-form', [DoctorController::class, 'editForm'])->name('doctors.edit.form');
    Route::put('doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');

    // Doctor Exceptions
    Route::post('doctors/{doctor}/exception', [DoctorController::class, 'addException'])->name('doctors.exception');
    Route::delete('doctors/{doctor}/exception/{exception}', [DoctorController::class, 'deleteException'])->name('doctors.exception.delete');
    Route::get('doctors/{doctor}/work-days', [DoctorController::class, 'getWorkDays'])
        ->name('work-days');
    Route::post('doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus']);

});
