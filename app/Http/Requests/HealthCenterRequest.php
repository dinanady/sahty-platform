<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $healthCenterId = $this->route('health_center') ? $this->route('health_center')->id : null;
        
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20|unique:health_centers,phone,' . $healthCenterId,
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'registration_number' => 'required|string|max:50|unique:health_centers,registration_number,' . $healthCenterId,
            'is_active' => 'boolean',
            'working_days' => 'nullable|array',
            'working_days.*' => 'string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'working_hours' => 'nullable|array',
        ];

        // التحقق من مواعيد العمل لكل يوم
        if ($this->has('working_days') && is_array($this->working_days)) {
            foreach ($this->working_days as $day) {
                $rules["working_hours.{$day}.start_time"] = 'required|date_format:H:i';
                $rules["working_hours.{$day}.end_time"] = 'required|date_format:H:i|after:working_hours.' . $day . '.start_time';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الوحدة الصحية مطلوب',
            'name.string' => 'اسم الوحدة الصحية يجب أن يكون نص',
            'name.max' => 'اسم الوحدة الصحية يجب ألا يزيد عن 255 حرف',
            'address.required' => 'العنوان مطلوب',
            'address.string' => 'العنوان يجب أن يكون نص',
            'address.max' => 'العنوان يجب ألا يزيد عن 500 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'رقم الهاتف يجب أن يكون نص',
            'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 20 حرف',
            'phone.unique' => 'رقم الهاتف موجود بالفعل',
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.exists' => 'المحافظة المحددة غير موجودة',
            'city_id.required' => 'المدينة مطلوبة',
            'city_id.exists' => 'المدينة المحددة غير موجودة',
            'latitude.numeric' => 'خط العرض يجب أن يكون رقم',
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.numeric' => 'خط الطول يجب أن يكون رقم',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
            'registration_number.required' => 'رقم التسجيل مطلوب',
            'registration_number.string' => 'رقم التسجيل يجب أن يكون نص',
            'registration_number.max' => 'رقم التسجيل يجب ألا يزيد عن 50 حرف',
            'registration_number.unique' => 'رقم التسجيل موجود بالفعل',
            'working_days.array' => 'أيام العمل يجب أن تكون مصفوفة',
            'working_days.*.in' => 'اليوم يجب أن يكون من الأيام المحددة',
            'working_hours.*.start_time.required' => 'وقت البداية مطلوب',
            'working_hours.*.start_time.date_format' => 'صيغة وقت البداية غير صحيحة (HH:MM)',
            'working_hours.*.end_time.required' => 'وقت النهاية مطلوب',
            'working_hours.*.end_time.date_format' => 'صيغة وقت النهاية غير صحيحة (HH:MM)',
            'working_hours.*.end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // التحقق من أن المدينة تنتمي للمحافظة المحددة
            if ($this->city_id && $this->governorate_id) {
                $cityBelongsToGovernorate = \App\Models\City::where('id', $this->city_id)
                    ->where('governorate_id', $this->governorate_id)
                    ->exists();
                
                if (!$cityBelongsToGovernorate) {
                    $validator->errors()->add('city_id', 'المدينة المحددة لا تنتمي للمحافظة المحددة');
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        // تجهيز working_hours بالشكل الصحيح
        $workingHours = [];
        if ($this->has('working_days') && is_array($this->working_days)) {
            foreach ($this->working_days as $day) {
                if (isset($this->working_hours[$day])) {
                    $workingHours[$day] = [
                        'start_time' => $this->working_hours[$day]['start_time'] ?? null,
                        'end_time' => $this->working_hours[$day]['end_time'] ?? null,
                    ];
                }
            }
        }

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'working_hours' => $workingHours,
        ]);
    }
}