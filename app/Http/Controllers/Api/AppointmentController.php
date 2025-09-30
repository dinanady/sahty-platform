<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vaccine;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function getVaccines(Request $request)
    {
        // الرقم القومي
        $nationalId = $request->national_id;

        // استخراج تاريخ الميلاد من الرقم القومي
        $yearPrefix = (int) substr($nationalId, 0, 1) < 3 ? '19' : '20';
        $year = $yearPrefix . substr($nationalId, 1, 2);
        $month = substr($nationalId, 3, 2);
        $day = substr($nationalId, 5, 2);
        $birthDate = \Carbon\Carbon::createFromFormat('Y-m-d', "$year-$month-$day");

        // حساب السن بالشهور
        $ageInMonths = $birthDate->diffInMonths(now());

        // جلب اللقاحات المناسبة
        $vaccines = Vaccine::age($ageInMonths)
            ->with('healthCenters.city.governorate')
            ->get();

        return response()->json([
            'age_in_months' => $ageInMonths,
            'vaccines' => $vaccines
        ]);
    }

    public function getCitiesAndCenters(Request $request)
    {
        $vaccineId = $request->vaccine_id;

        $vaccine = Vaccine::with(['healthCenters.city.governorate'])->findOrFail($vaccineId);

        // فلترة المراكز اللي فيها جرعات متاحة
        $centers = $vaccine->healthCenters->filter(function ($center) {
            return $center->pivot->availability == 1;
        });

        // جلب المدن من المراكز المتاحة
        $cities = $centers->map(function ($center) {
            return $center->city;
        })->unique('id')->values();

        // جلب المحافظات من المدن
        $governorates = $cities->map(function ($city) {
            return $city->governorate;
        })->unique('id')->values();

        return response()->json([
            'cities' => $cities,
            'centers' => $centers,
            'governorates' => $governorates
        ]);
    }

    public function getAvailableTimes(Request $request)
    {

        $centerId = $request->health_center_id;
        $date = $request->appointment_date;

        $startTime = \Carbon\Carbon::createFromTime(9, 0);
        $endTime = \Carbon\Carbon::createFromTime(13, 0);
        $interval = 15; // دقيقة

        $times = [];
        while ($startTime->lt($endTime)) {
            $times[] = $startTime->format('H:i');
            $startTime->addMinutes($interval);
        }

        // جلب المواعيد المحجوزة
        $booked = Appointment::where('health_center_id', $centerId)
            ->where('appointment_date', $date)
            ->pluck('appointment_time')
            ->map(fn($time) => \Carbon\Carbon::parse($time)->format('H:i'))
            ->toArray();

        // فلترة المواعيد المتاحة
        $availableTimes = array_diff($times, $booked);
        Log::info('Booked times and available times', [
            'booked' => $booked,
            'available' => $availableTimes,
        ]);

        return response()->json(array_values($availableTimes));
    }

    public function bookAppointment(Request $request)
    {
        $alreadyTaken = Appointment::where('national_id', $request->national_id)
            ->where('vaccine_id', $request->vaccine_id)
            ->where('status', 'مكتمل')
            ->exists();

        if ($alreadyTaken) {
            return response()->json([
                'message' => 'الطفل ده بالفعل حصل على نفس اللقاح من قبل'
            ], 422);
        }

        $request->validate([
            'child_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14',
            'phone_number' => 'required|string|min:10|max:15',
            'vaccine_id' => 'required|exists:vaccines,id',
            'health_center_id' => 'required|exists:health_centers,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|string',
            'dose_number' => 'nullable|integer',
            'notes' => 'nullable|string'
        ]);

        // التأكد من أن الميعاد متاح
        $exists = Appointment::where('health_center_id', $request->health_center_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'الموعد محجوز بالفعل'], 422);
        }

        // استخراج تاريخ الميلاد من الرقم القومي
        $nationalId = $request->national_id;
        $yearPrefix = (int) substr($nationalId, 0, 1) < 3 ? '19' : '20';
        $year = $yearPrefix . substr($nationalId, 1, 2);
        $month = substr($nationalId, 3, 2);
        $day = substr($nationalId, 5, 2);
        $birthDate = \Carbon\Carbon::createFromFormat('Y-m-d', "$year-$month-$day");

        $appointmentData = [
            'child_name' => $request->child_name,
            'child_birth_date' => $birthDate,
            'national_id' => $request->national_id,
            'phone_number' => $request->phone_number,
            'vaccine_id' => $request->vaccine_id,
            'health_center_id' => $request->health_center_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'dose_number' => $request->dose_number ?? 1,
            'notes' => $request->notes,
        ];

        $appointment = Appointment::create($appointmentData);

        // خصم جرعة من المركز
        $vaccine = Vaccine::findOrFail($request->vaccine_id);
        $center = $vaccine->healthCenters()->where('health_center_id', $request->health_center_id)->first();

        if ($center && $center->pivot->available_doses > 0) {
            $center->pivot->decrement('available_doses', 1);
        }

        return response()->json([
            'message' => 'تم حجز الموعد بنجاح',
            'appointment' => $appointment->load(['vaccine', 'healthCenter.city.governorate'])
        ], 201);
    }
}
