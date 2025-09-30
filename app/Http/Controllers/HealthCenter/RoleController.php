<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return $parts[1] ?? 'other';
        });

        return view('roles.manage', compact('roles', 'permissions'));
    }

    public function store(RoleStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            if ($request->filled('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            Log::info('Role created', ['role_id' => $role->id, 'by' => auth()->id()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الدور بنجاح',
        ]);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');

        return response()->json([
            'success' => true,
            'role' => $role,
            'rolePermissions' => $role->permissions->pluck('id')->toArray(),
        ]);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        DB::transaction(function () use ($request, $role) {
            $role->update([
                'name' => $request->name,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions ?? []);
            }

            Log::info('Role updated', ['role_id' => $role->id, 'by' => auth()->id()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الدور بنجاح',
        ]);
    }

    /**
     * حذف الدور
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف هذا الدور لأنه مرتبط بـ ' . $role->users()->count() . ' مستخدم',
            ], 403);
        }

        DB::transaction(function () use ($role) {
            $role->syncPermissions([]);
            $role->delete();

            Log::info('Role deleted', ['role_id' => $role->id, 'by' => auth()->id()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الدور بنجاح',
        ]);
    }

}
