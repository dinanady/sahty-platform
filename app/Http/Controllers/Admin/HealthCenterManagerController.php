<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HealthCenter;
use App\Services\AuthService;
use App\Http\Requests\StoreHealthCenterManagerRequest;
use App\Http\Requests\UpdateHealthCenterManagerRequest;
use Illuminate\Support\Facades\Hash;

class HealthCenterManagerController extends Controller
{
    /**
     * Display a listing of health center managers
     */
    public function index()
    {
        $managers = User::where('role', 'health_center_manager')
            ->with('healthCenter')
            ->paginate(15);
            
        return view('admin.health-center-managers.index', compact('managers'));
    }

    /**
     * Show the form for creating a new health center manager
     */
    public function create()
    {
        $healthCenters = HealthCenter::all();
        return view('admin.health-center-managers.create', compact('healthCenters'));
    }

    /**
     * Store a newly created health center manager
     */
    public function store(StoreHealthCenterManagerRequest $request)
    {
        $authService = new AuthService();
        $user = $authService->createUser([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'password' => $request->password,
            'role' => 'health_center_manager',
            'health_center_id' => $request->health_center_id,
            'is_verified' => true,
        ]);

        return redirect()->route('admin.health-center-managers.index')
            ->with('success', 'تم إنشاء حساب مدير الوحدة الصحية بنجاح');
    }

    /**
     * Display the specified health center manager
     */
    public function show(User $healthCenterManager)
    {
        if ($healthCenterManager->role !== 'health_center_manager') {
            abort(404);
        }
        
        $healthCenterManager->load('healthCenter');
        return view('admin.health-center-managers.show', compact('healthCenterManager'));
    }

    /**
     * Show the form for editing the specified health center manager
     */
    public function edit(User $healthCenterManager)
    {
        if ($healthCenterManager->role !== 'health_center_manager') {
            abort(404);
        }
        
        $healthCenters = HealthCenter::all();
        return view('admin.health-center-managers.edit', compact('healthCenterManager', 'healthCenters'));
    }

    /**
     * Update the specified health center manager
     */
    public function update(UpdateHealthCenterManagerRequest $request, User $healthCenterManager)
    {
        if ($healthCenterManager->role !== 'health_center_manager') {
            abort(404);
        }

        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'health_center_id' => $request->health_center_id,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $healthCenterManager->update($updateData);

        return redirect()->route('admin.health-center-managers.index')
            ->with('success', 'تم تحديث بيانات مدير الوحدة الصحية بنجاح');
    }

    /**
     * Remove the specified health center manager
     */
    public function destroy(User $healthCenterManager)
    {
        if ($healthCenterManager->role !== 'health_center_manager') {
            abort(404);
        }

        $healthCenterManager->delete();

        return redirect()->route('admin.health-center-managers.index')
            ->with('success', 'تم حذف مدير الوحدة الصحية بنجاح');
    }

    /**
     * Toggle manager status (active/inactive)
     */
    public function toggleStatus(User $healthCenterManager)
    {
        if ($healthCenterManager->role !== 'health_center_manager') {
            abort(404);
        }

        $healthCenterManager->update([
            'is_active' => !$healthCenterManager->is_active
        ]);

        $status = $healthCenterManager->is_active ? 'مفعل' : 'معطل';
        
        return redirect()->back()->with('success', "تم تغيير حالة المدير إلى {$status}");
    }

    /**
     * Assign health center to manager
     */
    public function assignHealthCenter(Request $request, User $healthCenterManager)
    {
        if ($healthCenterManager->role !== 'health_center_manager') {
            abort(404);
        }

        $request->validate([
            'health_center_id' => 'required|exists:health_centers,id'
        ]);

        $healthCenterManager->update([
            'health_center_id' => $request->health_center_id
        ]);

        return redirect()->back()->with('success', 'تم تعيين الوحدة الصحية للمدير بنجاح');
    }
}