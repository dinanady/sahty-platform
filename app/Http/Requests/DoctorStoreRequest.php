<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'schedules' => 'required|array|min:1',
            'schedules.*.enabled' => 'sometimes|in:0,1',
            'schedules.*.day_of_week' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'schedules.*.start_time' => 'required_if:schedules.*.enabled,1|nullable|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.enabled,1|nullable|date_format:H:i|after:schedules.*.start_time',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'specialty.required' => 'التخصص مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل',
            'schedules.required' => 'يجب تحديد جدول العمل',
            'schedules.min' => 'يجب تحديد جدول العمل',
            'schedules.*.end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
        ];
    }
}
