<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use App\Models\City;
use App\Models\HealthCenter;

class AdminDashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     */


    //   public function __construct()
    // {
    //     // $this->middleware('auth');
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->isAdmin()) {
    //             abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
    //         }
    //         return $next($request);
    //     });
    // }
    public function index()
    {
         $stats = [
            'total_governorates' => Governorate::count(),
            'total_cities' => City::count(),
            'total_health_centers' => HealthCenter::count(),
            // 'total_managers' => User::where('role', 'health_center_manager')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    
    }

    /**
     * عرض تقارير وإحصائيات
     */
    
}