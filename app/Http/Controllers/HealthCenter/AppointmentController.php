<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Models\Appointment;
use App\Models\Vaccine;
use App\Models\HealthCenter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AppointmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('hc-view-appointments'), only: ['index', 'show']),
            new Middleware(PermissionMiddleware::using('hc-create-appointments'), only: ['create', 'store', 'createModal', 'getChildByNationalId']),
            new Middleware(PermissionMiddleware::using('hc-edit-appointments'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('hc-delete-appointments'), only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $healthCenter = HealthCenter::where('id', Auth::user()->health_center_id)->first();

        $query = Appointment::with(['vaccine', 'healthCenter'])
            ->forUserHealthCenter()
            ->byChildName($request->child_name)
            ->byNationalId($request->national_id)
            ->byVaccine($request->vaccine_id)
            ->byStatus($request->status)
            ->byAppointmentDate($request->appointment_date)
            ->byChildBirthDate($request->child_birth_date)
            ->byDoseNumber($request->dose_number);

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        $vaccines = $healthCenter->vaccines;
        $statuses = ['مجدول', 'مكتمل', 'ملغي', 'لم يحضر'];

        // إذا كان الطلب AJAX، نرجع JSON
        if ($request->ajax()) {
            return response()->json([
                'appointments' => $appointments,
                'vaccines' => $vaccines,
                'statuses' => $statuses
            ]);
        }

        return view('health-center.appointments.index', compact('appointments', 'vaccines', 'statuses'));
    }

    public function store(AppointmentStoreRequest $request)
    {
        try {
            $data = $request->validated();
            $data['health_center_id'] = auth()->user()->health_center_id;

            $appointment = Appointment::create($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الحجز بنجاح',
                    'appointment' => $appointment->load(['vaccine', 'healthCenter'])
                ]);
            }

            return redirect()->route('health-center.appointments.index')
                ->with('success', 'تم إنشاء الحجز بنجاح');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الحجز'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء إنشاء الحجز');
        }
    }

    public function show(Appointment $appointment)
    {
        $this->checkAppointmentAccess($appointment);

        $appointment->load(['vaccine', 'healthCenter']);

        if (request()->ajax()) {
            return response()->json([
                'appointment' => $appointment
            ]);
        }

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->checkAppointmentAccess($appointment);

        $appointment->load(['vaccine', 'healthCenter']);

        if (request()->ajax()) {
            return response()->json([
                'appointment' => $appointment,
                'vaccines' => Vaccine::all(),
                'healthCenters' => HealthCenter::all(),
                'statuses' => ['مجدول', 'مكتمل', 'ملغي', 'لم يحضر']
            ]);
        }

        $vaccines = Vaccine::all();
        $healthCenters = HealthCenter::all();
        $statuses = ['مجدول', 'مكتمل', 'ملغي', 'لم يحضر'];

        return view('appointments.edit', compact('appointment', 'vaccines', 'healthCenters', 'statuses'));
    }

    public function update(AppointmentUpdateRequest $request, Appointment $appointment)
    {
        $this->checkAppointmentAccess($appointment);

        try {
            $appointment->update($request->validated());
            $appointment->load(['vaccine', 'healthCenter']);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الحجز بنجاح',
                    'appointment' => $appointment
                ]);
            }

            return redirect()->route('health-center.appointments.index')
                ->with('success', 'تم تحديث الحجز بنجاح');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الحجز'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء تحديث الحجز');
        }
    }

    public function destroy(Appointment $appointment)
    {
        $this->checkAppointmentAccess($appointment);

        try {
            $appointment->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الحجز بنجاح'
                ]);
            }

            return redirect()->route('health-center.appointments.index')
                ->with('success', 'تم حذف الحجز بنجاح');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الحجز'
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف الحجز');
        }
    }

    public function createModal()
    {
        $vaccines = Vaccine::all();
        $healthCenters = HealthCenter::all();

        if (request()->ajax()) {
            return response()->json([
                'vaccines' => $vaccines,
                'healthCenters' => $healthCenters
            ]);
        }

        return view('appointments.create', compact('vaccines', 'healthCenters'));
    }

    private function checkAppointmentAccess($appointment)
    {
        if (
            Auth::check() && Auth::user()->health_center_id &&
            $appointment->health_center_id != Auth::user()->health_center_id
        ) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا الحجز');
        }
    }

    public function getChildByNationalId(Request $request)
    {
        $request->validate([
            'national_id' => 'required|string|size:14'
        ]);

        try {
            $yearPrefix = (int) substr($request->national_id, 0, 1) < 3 ? '19' : '20';
            $year = $yearPrefix . substr($request->national_id, 1, 2);
            $month = substr($request->national_id, 3, 2);
            $day = substr($request->national_id, 5, 2);

            $birthDate = \Carbon\Carbon::createFromFormat('Y-m-d', "$year-$month-$day");

            return response()->json([
                'success' => true,
                'birth_date' => $birthDate->format('Y-m-d'),
                'age' => $birthDate->age
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الرقم القومي غير صحيح'
            ]);
        }
    }
}
