<?php
namespace Database\Seeders;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // تأكد من وجود Role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ], [
            'display_name' => 'مدير النظام'
        ]);

        // إنشاء المستخدم
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '01012345678',
                'national_id' => '12345678901234',
                'password' => Hash::make('password123'),
                'role' => 'admin', // للـ backward compatibility
                'is_verified' => true,
            ]
        );

        // إعطاء الدور باستخدام Spatie
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        $this->command->info('Admin user created successfully with role: ' . $user->getRoleNames()->implode(', '));
    }
}
