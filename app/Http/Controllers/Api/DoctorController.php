<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        // Build the query
        $query = Doctor::with(['schedules', 'exceptions'])
            ->where('health_center_id', $request->health_center_id)
            ->where('is_active', true);

        // Filter by date if provided
        if ($request->has('date')) {
            $date = $request->date;
            $query->whereHas('schedules', function ($q) use ($date) {
                $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));
                $q->where('day_of_week', $dayOfWeek)->where('available', true);
            })->orWhereHas('exceptions', function ($q) use ($date) {
                $q->where('exception_date', $date)->where('type', 'available');
            });
        }

        // Fetch doctors
        $doctors = $query->get();

        // Transform the response to include schedules for the specific date
        $doctors->each(function ($doctor) use ($request) {
            if ($request->has('date')) {
                $doctor->schedules = $doctor->isAvailableOn($request->date);
            }
        });

        return DoctorResource::collection($doctors);
    }
}
