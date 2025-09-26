<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthCenterRequest;
use App\Models\Governorate;
use App\Models\HealthCenter;
use Illuminate\Http\Request;

class HealthCenterController extends Controller
{
       public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }
    public function index()
    {
        $healthCenters = HealthCenter::with(['governorate', 'city'])
            ->withCount(['doctors', 'appointments'])
            ->paginate(15);
        return view('admin.health_centers.index', compact('healthCenters'));
    }

    public function create()
    {
        $governorates = Governorate::with('cities')->get();
        return view('admin.health_centers.create', compact('governorates'));
    }

    public function store(HealthCenterRequest $request)
    {
        HealthCenter::create($request->validated());
        return redirect()->route('admin.health-centers.index')
            ->with('success', 'تم إضافة الوحدة الصحية بنجاح');
    }

    public function edit(HealthCenter $healthCenter)
    {
        $governorates = Governorate::with('cities')->get();
        return view('admin.health_centers.edit', compact('healthCenter', 'governorates'));
    }

    public function update(HealthCenterRequest $request, HealthCenter $healthCenter)
    {
        $healthCenter->update($request->validated());
        return redirect()->route('admin.health-centers.index')
            ->with('success', 'تم تحديث الوحدة الصحية بنجاح');
    }

    public function destroy(HealthCenter $healthCenter)
    {
        if ($healthCenter->appointments()->count() > 0 || $healthCenter->doctors()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الوحدة الصحية لأنها تحتوي على حجوزات أو أطباء');
        }

        $healthCenter->delete();
        return redirect()->route('admin.health-centers.index')
            ->with('success', 'تم حذف الوحدة الصحية بنجاح');
    }
}