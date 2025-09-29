<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'child_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:appointments,national_id',
            'child_birth_date' => 'nullable|date',
            'vaccine_id' => 'required|exists:vaccines,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
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
            'child_birth_date.date' => 'تاريخ الميلاد يجب أن يكون تاريخ صحيح',
            'vaccine_id.required' => 'نوع اللقاح مطلوب',
            'vaccine_id.exists' => 'نوع اللقاح غير صحيح',
            'health_center_id.required' => 'المركز الصحي مطلوب',
            'health_center_id.exists' => 'المركز الصحي غير صحيح',
            'appointment_date.required' => 'تاريخ الموعد مطلوب',
            'appointment_date.after_or_equal' => 'تاريخ الموعد يجب أن يكون اليوم أو بعده',
            'appointment_time.required' => 'وقت الموعد مطلوب',
            'dose_number.required' => 'رقم الجرعة مطلوب',
            'dose_number.min' => 'رقم الجرعة يجب أن يكون 1 على الأقل',
        ];
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
