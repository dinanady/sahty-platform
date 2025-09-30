<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/RolesAndPermissionsSeeder.php

    public function run()
    {
        $permissions = [
            ['name' => 'hc-view-drugs', 'display_name' => 'عرض الأدوية'],
            ['name' => 'hc-create-drugs', 'display_name' => 'إضافة أدوية'],
            ['name' => 'hc-edit-drugs', 'display_name' => 'تعديل الأدوية'],
            ['name' => 'hc-delete-drugs', 'display_name' => 'حذف الأدوية'],
            ['name' => 'hc-toggle-drug-status', 'display_name' => 'تفعيل/تعطيل الأدوية'],
            ['name' => 'hc-submit-new-drug', 'display_name' => 'طلب دواء جديد'],
            ['name' => 'hc-resubmit-drug', 'display_name' => 'إعادة تقديم طلب دواء'],

            // Vaccines
            ['name' => 'hc-view-vaccines', 'display_name' => 'عرض التطعيمات'],
            ['name' => 'hc-create-vaccines', 'display_name' => 'إضافة تطعيمات'],
            ['name' => 'hc-edit-vaccines', 'display_name' => 'تعديل التطعيمات'],
            ['name' => 'hc-delete-vaccines', 'display_name' => 'حذف التطعيمات'],
            ['name' => 'hc-update-vaccine-availability', 'display_name' => 'تحديث توافر التطعيمات'],

            // Appointments
            ['name' => 'hc-view-appointments', 'display_name' => 'عرض المواعيد'],
            ['name' => 'hc-create-appointments', 'display_name' => 'إنشاء مواعيد'],
            ['name' => 'hc-edit-appointments', 'display_name' => 'تعديل المواعيد'],
            ['name' => 'hc-delete-appointments', 'display_name' => 'حذف المواعيد'],

            // Doctors
            ['name' => 'hc-view-doctors', 'display_name' => 'عرض الأطباء'],
            ['name' => 'hc-create-doctors', 'display_name' => 'إضافة أطباء'],
            ['name' => 'hc-edit-doctors', 'display_name' => 'تعديل الأطباء'],
            ['name' => 'hc-delete-doctors', 'display_name' => 'حذف الأطباء'],
            ['name' => 'hc-toggle-doctor-status', 'display_name' => 'تفعيل/تعطيل الأطباء'],
            ['name' => 'hc-manage-doctor-exceptions', 'display_name' => 'إدارة اعتذارات الأطباء'],

            // Users & Roles Management
            ['name' => 'hc-view-users', 'display_name' => 'عرض المستخدمين'],
            ['name' => 'hc-create-users', 'display_name' => 'إضافة مستخدمين'],
            ['name' => 'hc-edit-users', 'display_name' => 'تعديل المستخدمين'],
            ['name' => 'hc-delete-users', 'display_name' => 'حذف المستخدمين'],
            ['name' => 'hc-assign-roles', 'display_name' => 'تعيين الأدوار للمستخدمين'],

            ['name' => 'hc-view-roles', 'display_name' => 'عرض الأدوار'],
            ['name' => 'hc-create-roles', 'display_name' => 'إنشاء أدوار'],
            ['name' => 'hc-edit-roles', 'display_name' => 'تعديل الأدوار'],
            ['name' => 'hc-delete-roles', 'display_name' => 'حذف الأدوار'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'guard_name' => 'web'
            ]);
        }

        $roles = [
            [
                'name' => 'hc-admin',
                'display_name' => 'مدير المركز الصحي',
                'permissions' => Permission::where('name', 'like', 'hc-%')->get()
            ],
            [
                'name' => 'hc-drug-manager',
                'display_name' => 'مسؤول الأدوية',
                'permissions' => [
                    'hc-view-drugs',
                    'hc-create-drugs',
                    'hc-edit-drugs',
                    'hc-delete-drugs',
                    'hc-toggle-drug-status',
                    'hc-submit-new-drug',
                    'hc-resubmit-drug'
                ]
            ],
            [
                'name' => 'hc-vaccine-manager',
                'display_name' => 'مسؤول التطعيمات',
                'permissions' => [
                    'hc-view-vaccines',
                    'hc-create-vaccines',
                    'hc-edit-vaccines',
                    'hc-delete-vaccines',
                    'hc-update-vaccine-availability',
                    'hc-view-appointments',
                    'hc-create-appointments',
                    'hc-edit-appointments',
                    'hc-delete-appointments'
                ]
            ],
            [
                'name' => 'hc-doctor-manager',
                'display_name' => 'مسؤول الأطباء',
                'permissions' => [
                    'hc-view-doctors',
                    'hc-create-doctors',
                    'hc-edit-doctors',
                    'hc-delete-doctors',
                    'hc-toggle-doctor-status',
                    'hc-manage-doctor-exceptions'
                ]
            ],
            [
                'name' => 'hc-receptionist',
                'display_name' => 'موظف استقبال',
                'permissions' => [
                    'hc-view-drugs',
                    'hc-view-vaccines',
                    'hc-view-appointments',
                    'hc-create-appointments',
                    'hc-view-doctors'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'display_name' => $roleData['display_name'],
                'guard_name' => 'web'
            ]);

            if ($roleData['permissions'] instanceof \Illuminate\Database\Eloquent\Collection) {
                $role->givePermissionTo($roleData['permissions']);
            } else {
                $role->givePermissionTo($roleData['permissions']);
            }
        }
    }
}
