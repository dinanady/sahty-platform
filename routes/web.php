<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\HealthCenterController;
use Illuminate\Support\Facades\Route;
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// طرق إضافية للإدمن
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/create-manager', [AuthController::class, 'createHealthCenterManager'])
         ->name('admin.create-manager');
});


Route::prefix('admin')->name('admin.')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // التقارير
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');

    // إدارة المحافظات
    Route::resource('governorates', GovernorateController::class)->except(['show'])->names([
        'index' => 'governorates.index',
        'create' => 'governorates.create',
        'store' => 'governorates.store',
        'edit' => 'governorates.edit',
        'update' => 'governorates.update',
        'destroy' => 'governorates.destroy',
    ]);

    // إدارة المدن
    Route::resource('cities', CityController::class)->except(['show'])->names([
        'index' => 'cities.index',
        'create' => 'cities.create',
        'store' => 'cities.store',
        'edit' => 'cities.edit',
        'update' => 'cities.update',
        'destroy' => 'cities.destroy',
    ]);

    // استرجاع المدن بناءً على المحافظة (API)
    Route::get('/cities-by-governorate', [CityController::class, 'getCitiesByGovernorate'])->name('cities.by-governorate');

    // إدارة الوحدات الصحية
    Route::resource('health-centers', HealthCenterController::class)->except(['show'])->names([
        'index' => 'health-centers.index',
        'create' => 'health-centers.create',
        'store' => 'health-centers.store',
        'edit' => 'health-centers.edit',
        'update' => 'health-centers.update',
        'destroy' => 'health-centers.destroy',
    ]);
});

// مسار افتراضي للصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});