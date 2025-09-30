<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DrugApprovalController;
use App\Http\Controllers\Admin\HealthCenterController;
use App\Http\Controllers\Admin\HealthCenterManagerController;
use App\Http\Controllers\Admin\DrugController;
use App\Http\Controllers\Admin\VaccineController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/health_center_web.php';

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
    Route::resource('health-centers', HealthCenterController::class)->names([
        'index' => 'health-centers.index',
        'create' => 'health-centers.create',
        'store' => 'health-centers.store',
        'edit' => 'health-centers.edit',
        'update' => 'health-centers.update',
        'destroy' => 'health-centers.destroy',
        'show' => 'health-centers.show',
    ]);
    Route::resource('health-center-managers', HealthCenterManagerController::class)->names([
        'index' => 'health-center-managers.index',
        'create' => 'health-center-managers.create',
        'store' => 'health-center-managers.store',
        'edit' => 'health-center-managers.edit',
        'update' => 'health-center-managers.update',
        'destroy' => 'health-center-managers.destroy',
        'show' => 'health-center-managers.show',
    ]);
    Route::post('/health-center-managers/{healthCenterManager}/toggle-status', [HealthCenterManagerController::class, 'toggleStatus'])
        ->name('health-center-managers.toggle-status');
    Route::post('/health-center-managers/{healthCenterManager}/assign-health-center', [HealthCenterManagerController::class, 'assignHealthCenter'])
        ->name('health-center-managers.assign-health-center');
    Route::resource('drugs', DrugController::class);
    Route::post('drugs/{drug}/assign', [DrugController::class, 'assignToHealthCenter'])
        ->name('drugs.assign');

    // اللقاحات
    Route::resource('vaccines', VaccineController::class);
    Route::post('vaccines/{vaccine}/assign', [VaccineController::class, 'assignToHealthCenter'])
        ->name('vaccines.assign');

    Route::get('request-drugs', [DrugApprovalController::class, 'index'])->name('request-drugs');
    Route::put('approve-drugs/{drug}', [DrugApprovalController::class, 'updateStatus'])->name('approve-drugs.update');
    Route::get('approved-drugs', [DrugApprovalController::class, 'approved'])->name('approved-drugs');
    Route::get('rejected-drugs', [DrugApprovalController::class, 'rejected'])->name('rejected-drugs');
});

// مسار افتراضي للصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});
