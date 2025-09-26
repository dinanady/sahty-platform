<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ===== GovernorateRequest =====
class GovernorateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        // في حالة التحديث، تجاهل المحافظة الحالية في فحص التكرار
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules['name'] .= '|unique:governorates,name,' . $this->route('governorate')->id;
        } else {
            $rules['name'] .= '|unique:governorates,name';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المحافظة مطلوب',
            'name.string' => 'اسم المحافظة يجب أن يكون نص',
            'name.max' => 'اسم المحافظة يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم المحافظة موجود بالفعل',
        ];
    }
}