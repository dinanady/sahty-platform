<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $userId, 'max:255'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $userId],
            'national_id' => ['nullable', 'string', 'max:50', 'unique:users,national_id,' . $userId],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'is_active' => ['nullable', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المستخدم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل',
            'national_id.unique' => 'الرقم الوطني مستخدم من قبل',
        ];
    }
}
