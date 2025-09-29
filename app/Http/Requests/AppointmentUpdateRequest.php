<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
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
        $appointmentId = $this->route('appointment')->id;

        return [
            'child_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:appointments,national_id,' . $appointmentId,
            'child_birth_date' => 'nullable|date',
            'vaccine_id' => 'required|exists:vaccines,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:مجدول,مكتمل,ملغي,لم يحضر',
            'dose_number' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'child_name.required' => 'اسم الطفل مطلوب',
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقم',
            'national_id.unique' => 'الرقم القومي مسجل بالفعل',
            'child_birth_date.required' => 'تاريخ ميلاد الطفل مطلوب',
            'vaccine_id.required' => 'نوع اللقاح مطلوب',
            'health_center_id.required' => 'المركز الصحي مطلوب',
            'appointment_date.required' => 'تاريخ الموعد مطلوب',
            'appointment_time.required' => 'وقت الموعد مطلوب',
            'status.required' => 'حالة الحجز مطلوبة',
            'status.in' => 'حالة الحجز غير صحيحة',
            'dose_number.required' => 'رقم الجرعة مطلوب',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->national_id && $this->child_birth_date) {
                $calculatedBirthDate = $this->calculateBirthDateFromNationalId($this->national_id);
                $providedBirthDate = Carbon::parse($this->child_birth_date);

                if (!$calculatedBirthDate->isSameDay($providedBirthDate)) {
                    $validator->errors()->add(
                        'national_id',
                        'الرقم القومي لا يتطابق مع تاريخ الميلاد المقدم'
                    );
                }
            }
        });
    }

    private function calculateBirthDateFromNationalId($nationalId)
    {
        $yearPrefix = (int) substr($nationalId, 0, 1) < 3 ? '19' : '20';
        $year = $yearPrefix . substr($nationalId, 1, 2);
        $month = substr($nationalId, 3, 2);
        $day = substr($nationalId, 5, 2);

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
