<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DrugController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\HealthCenterController;

Route::get('/governorates', [GovernorateController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']); // مع فلترة بالمحافظة
Route::get('/healthcare-centers', [HealthCenterController::class, 'index']); // مع فلترة بالمدينة
Route::get('/drugs', [DrugController::class, 'index']); // مع فلترة بمركز الرعاية الصحية
