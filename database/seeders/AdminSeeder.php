<?php
namespace Database\Seeders;

use App\Services\AuthService;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authService = new AuthService();

        // إنشاء مستخدم أدمن
        $authService->createUser([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone' => '01012345678',
            'national_id' => '12345678901234',
            'password' => 'password123', // سيتم تشفيرها باستخدام Hash في AuthService
            'role' => 'admin',
            'health_center_id' => null,
            'is_verified' => true,
        ]);
    }
}