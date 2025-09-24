<?php

use App\Http\Controllers\Api\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DrugController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\HealthCenterController;

Route::get('/governorates', [GovernorateController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('/healthcare-centers', [HealthCenterController::class, 'index']);
Route::get('/drugs', [DrugController::class, 'index']);
Route::get('/doctors', [DoctorController::class, 'index']);
