<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * التحقق من صحة بيانات الدخول
     */
    public function validateCredentials($login, $password)
    {
        $user = $this->findUserByMultipleFields($login);
        
        if (!$user) {
            return false;
        }

        return Hash::check($password, $user->password) ? $user : false;
    }

    /**
     * البحث عن المستخدم بعدة طرق
     */
    public function findUserByMultipleFields($login)
    {
        return User::where(function($query) use ($login) {
            $query->where('email', $login)
                  ->orWhere('phone', $login)
                  ->orWhere('national_id', $login);
        })->first();
    }

    /**
     * إنشاء حساب جديد
     */
    public function createUser($data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'national_id' => $data['national_id'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'health_center_manager',
            'health_center_id' => $data['health_center_id'] ?? null,
            'is_verified' => $data['is_verified'] ?? false,
        ]);
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(User $user, $newPassword)
    {
        return $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }

    /**
     * التحقق من قوة كلمة المرور
     */
    public function validatePasswordStrength($password)
    {
        $rules = [
            'min_length' => strlen($password) >= 6,
            'has_letter' => preg_match('/[a-zA-Z]/', $password),
            'has_number' => preg_match('/[0-9]/', $password),
        ];

        return array_filter($rules);
    }
}