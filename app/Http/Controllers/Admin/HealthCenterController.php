<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthCenterRequest;
use App\Models\Governorate;
use App\Models\HealthCenter;
use App\Models\User;
use Illuminate\Http\Request;

class HealthCenterController extends Controller
{
    public function index()
    {
        $healthCenters = HealthCenter::with(['city.governorate', 'manager'])
            ->withCount(['doctors', 'appointments'])
            ->paginate(15);
        return view('admin.health_centers.index', compact('healthCenters'));
    }
public function create()
{
    $governorates = Governorate::with('cities')->get();
    
    // للتأكد من وجود المدن
    foreach($governorates as $gov) {
        \Log::info("Governorate: {$gov->name}, Cities count: " . $gov->cities->count());
    }
    
    return view('admin.health_centers.create', compact('governorates'));
}
public function store(HealthCenterRequest $request)
{
    // فقط إنشاء الوحدة الصحية بدون ربط مدير
    HealthCenter::create($request->validated());
    
    return redirect()->route('admin.health-centers.index')
        ->with('success', 'تم إضافة الوحدة الصحية بنجاح');
}
// أضف هذا في Controller
public function show(HealthCenter $healthCenter)
{
    $healthCenter->load([
        'city.governorate',
        'manager',
        'doctors',
        'appointments',
        'drugs' => function($query) {
            $query->withPivot('availability', 'stock');
        },
        'vaccines' => function($query) {
            $query->withPivot('availability', 'stock');
        }
    ]);
    
    return view('admin.health_centers.show', compact('healthCenter'));
}
public function edit(HealthCenter $healthCenter)
{
    $governorates = Governorate::with('cities')->get();
    // لا حاجة لجلب المديرين
    return view('admin.health_centers.edit', compact('healthCenter', 'governorates'));
}

public function update(HealthCenterRequest $request, HealthCenter $healthCenter)
{
    // فقط تحديث بيانات الوحدة بدون ربط مدير
    $healthCenter->update($request->validated());
    
    return redirect()->route('admin.health-centers.index')
        ->with('success', 'تم تحديث الوحدة الصحية بنجاح');
}
    public function destroy(HealthCenter $healthCenter)
    {
        // التحقق من وجود حجوزات أو أطباء
        if ($healthCenter->appointments()->count() > 0 || $healthCenter->doctors()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الوحدة الصحية لأنها تحتوي على حجوزات أو أطباء');
        }

        // إزالة الوحدة من المدير
        User::where('health_center_id', $healthCenter->id)->update([
            'health_center_id' => null
        ]);

        $healthCenter->delete();
        
        return redirect()->route('admin.health-centers.index')
            ->with('success', 'تم حذف الوحدة الصحية بنجاح');
    }
}