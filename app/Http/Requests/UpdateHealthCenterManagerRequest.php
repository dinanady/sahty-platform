<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthCenterManagerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $managerId = $this->route('health_center_manager')->id;
        
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $managerId,
            'phone' => 'required|string|unique:users,phone,' . $managerId . '|regex:/^01[0-2,5]{1}[0-9]{8}$/',
            'national_id' => 'required|string|size:14|unique:users,national_id,' . $managerId,
            'password' => 'nullable|string|min:6|confirmed',
            'health_center_id' => 'required|exists:health_centers,id|unique:users,health_center_id,' . $managerId,
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'اسم العائلة مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقم',
            'national_id.unique' => 'الرقم القومي مستخدم من قبل',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'health_center_id.required' => 'يجب اختيار الوحدة الصحية',
            'health_center_id.exists' => 'الوحدة الصحية المحددة غير موجودة',
            'health_center_id.unique' => 'هذه الوحدة الصحية لديها مدير بالفعل',
        ];
    }
}