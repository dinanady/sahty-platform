<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\HealthCenterController;
use App\Http\Controllers\Admin\HealthCenterManagerController;
use App\Http\Controllers\Admin\DrugController;
use App\Http\Controllers\Admin\VaccineController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/health_center_web.php';

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

// ⭐ إضافة middleware للـ admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // التقارير
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');

    // إدارة المحافظات
    Route::resource('governorates', GovernorateController::class)->except(['show']);

    // إدارة المدن
    Route::resource('cities', CityController::class)->except(['show']);

    // استرجاع المدن بناءً على المحافظة (API)
    Route::get('/cities-by-governorate', [CityController::class, 'getCitiesByGovernorate'])->name('cities.by-governorate');

    // إدارة الوحدات الصحية
    Route::resource('health-centers', HealthCenterController::class);
    
    // إدارة مديري الوحدات
    Route::resource('health-center-managers', HealthCenterManagerController::class);
    Route::post('/health-center-managers/{healthCenterManager}/toggle-status', [HealthCenterManagerController::class, 'toggleStatus'])
        ->name('health-center-managers.toggle-status');
    Route::post('/health-center-managers/{healthCenterManager}/assign-health-center', [HealthCenterManagerController::class, 'assignHealthCenter'])
        ->name('health-center-managers.assign-health-center');
    
    // إدارة الأدوية
    Route::resource('drugs', DrugController::class);
    Route::post('drugs/{drug}/assign', [DrugController::class, 'assignToHealthCenter'])
        ->name('drugs.assign');
    
    // إدارة اللقاحات
    Route::resource('vaccines', VaccineController::class);
    Route::post('vaccines/{vaccine}/assign', [VaccineController::class, 'assignToHealthCenter'])
        ->name('vaccines.assign');
});

// مسار افتراضي للصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});