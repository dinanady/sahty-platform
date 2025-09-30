<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('hc-view-users'), only: ['index']),
            new Middleware(PermissionMiddleware::using('hc-create-users'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('hc-edit-users'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('hc-delete-users'), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $users = User::with('roles.permissions')
            ->applyFilters($request->all())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::all();

        if ($request->ajax()) {
            return response()->json([
                'users' => [
                    'html' => view('health-center.users.partials.table-rows', compact('users'))->render(),
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total()
                ],
                'pagination' => $users->appends($request->all())->links()->render()
            ]);
        }

        return view('health-center.users.index', compact('users', 'roles'));
    }

    public function store(UserStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'health_center_id' => auth()->user()->health_center_id,
                'role' => auth()->user()->role,
                'national_id' => $request->national_id,
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($request->has('roles')) {
                $roles = \Spatie\Permission\Models\Role::whereIn('id', (array) $request->roles)->get();
                $user->syncRoles($roles);
            }

            Log::info('User created', ['user_id' => $user->id, 'by' => auth()->id()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المستخدم بنجاح'
        ]);
    }

    public function edit(User $user)
    {
        $user->load('roles');

        return response()->json([
            'success' => true,
            'user' => $user,
            'userRoles' => $user->roles->pluck('id')->toArray()
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            if ($request->has('roles')) {
                $roles = \Spatie\Permission\Models\Role::whereIn('id', (array) $request->roles)->get();
                $user->syncRoles($roles);
            }

            Log::info('User updated', ['user_id' => $user->id, 'by' => auth()->id()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المستخدم بنجاح'
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك حذف حسابك الخاص'
            ], 403);
        }

        DB::transaction(function () use ($user) {
            $user->syncRoles([]);
            $user->delete();

            Log::info('User deleted', ['user_id' => $user->id, 'by' => auth()->id()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المستخدم بنجاح'
        ]);
    }
}

