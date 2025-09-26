<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'governorate_id' => 'required|exists:governorates,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المدينة مطلوب',
            'name.string' => 'اسم المدينة يجب أن يكون نص',
            'name.max' => 'اسم المدينة يجب ألا يزيد عن 255 حرف',
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.exists' => 'المحافظة المحددة غير موجودة',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // التحقق من عدم تكرار اسم المدينة في نفس المحافظة
            $query = \App\Models\City::where('name', $this->name)
                ->where('governorate_id', $this->governorate_id);

            // في حالة التحديث، تجاهل المدينة الحالية
            if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
                $query->where('id', '!=', $this->route('city')->id);
            }

            if ($query->exists()) {
                $validator->errors()->add('name', 'هذه المدينة موجودة بالفعل في المحافظة المحددة');
            }
        });
    }
}
