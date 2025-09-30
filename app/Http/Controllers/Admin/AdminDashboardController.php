<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use App\Models\City;
use App\Models\HealthCenter;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'total_governorates' => Governorate::count(),
            'total_cities' => City::count(),
            'total_health_centers' => HealthCenter::count(),
            'active_health_centers' => HealthCenter::where('is_active', true)->count(),
            'inactive_health_centers' => HealthCenter::where('is_active', false)->count(),
            'total_managers' => User::where('role', 'health_center_manager')->count(),
            'active_managers' => User::where('role', 'health_center_manager')->where('is_active', true)->count(),
            'health_centers_without_manager' => HealthCenter::whereDoesntHave('manager')->count(),
        ];

        // أحدث الوحدات الصحية
        $recentHealthCenters = HealthCenter::with(['city.governorate', 'manager'])
            ->latest()
            ->take(5)
            ->get();

        // توزيع الوحدات حسب المحافظات
        $healthCentersByGovernorate = Governorate::withCount('healthCenters')
            ->orderBy('health_centers_count', 'desc')
            ->take(10)
            ->get();

        // مديرين بدون وحدات صحية
        $managersWithoutHealthCenter = User::where('role', 'health_center_manager')
            ->whereNull('health_center_id')
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentHealthCenters',
            'healthCentersByGovernorate',
            'managersWithoutHealthCenter'
        ));
    }
}