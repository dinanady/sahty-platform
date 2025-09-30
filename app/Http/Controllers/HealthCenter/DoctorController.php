<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorStoreRequest;
use App\Http\Requests\DoctorUpdateRequest;
use App\Models\Doctor;
use App\Models\DoctorScheduleException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DoctorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('hc-view-doctors'), only: ['index', 'showDetails', 'getWorkDays']),
            new Middleware(PermissionMiddleware::using('hc-create-doctors'), only: ['store']),
            new Middleware(PermissionMiddleware::using('hc-edit-doctors'), only: ['editForm', 'update']),
            new Middleware(PermissionMiddleware::using('hc-delete-doctors'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('hc-toggle-doctor-status'), only: ['toggleStatus']),
            new Middleware(PermissionMiddleware::using('hc-manage-doctor-exceptions'), only: ['addException', 'deleteException']),
        ];
    }

    public function index(Request $request)
    {
        $healthCenterId = Auth::user()->health_center_id;

        // ناخد الفلاتر من الريكوست
        $filters = $request->only(['name', 'specialty', 'status']);
        $perPage = $request->get('per_page', 15); // الافتراضي 15

        $doctors = Doctor::with(['healthCenter', 'schedules'])
            ->where('health_center_id', $healthCenterId)
            ->applyFilters($filters) // هنا بنستخدم الـ Trait
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // التخصصات للفلترة
        $specialties = Doctor::where('health_center_id', $healthCenterId)
            ->distinct()
            ->pluck('specialty');

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'html' => view('health-center.doctors.partials.table-rows', compact('doctors'))->render(),
                'pagination' => $doctors->links()->render()
            ]);
        }

        return view('health-center.doctors.index', compact('doctors', 'specialties', 'filters'));
    }


    public function store(DoctorStoreRequest $request)
    {
        $healthCenterId = Auth::user()->health_center_id;

        // التحقق من وجود يوم مفعل على الأقل
        $hasEnabledDay = false;
        if (isset($request->schedules)) {
            foreach ($request->schedules as $schedule) {
                if (isset($schedule['enabled']) && $schedule['enabled'] == '1') {
                    $hasEnabledDay = true;
                    break;
                }
            }
        }

        if (!$hasEnabledDay) {
            return response()->json([
                'success' => false,
                'errors' => ['schedules' => ['يجب اختيار يوم واحد على الأقل من أيام العمل']]
            ], 422);
        }

        // قاعدة الفاليديشن المعدلة
        $validated = $request->validated();
        try {
            DB::transaction(function () use ($validated, $healthCenterId) {
                $doctor = Doctor::create([
                    'name' => $validated['name'],
                    'specialty' => $validated['specialty'],
                    'phone' => $validated['phone'],
                    'health_center_id' => $healthCenterId,
                ]);

                foreach ($validated['schedules'] as $schedule) {
                    // حفظ فقط الأيام المفعلة
                    if (isset($schedule['enabled']) && $schedule['enabled'] == '1') {
                        $doctor->schedules()->create([
                            'day_of_week' => $schedule['day_of_week'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الطبيب بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الطبيب: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Doctor $doctor)
    {
        try {
            $doctor->is_active = !$doctor->is_active;
            $doctor->save();

            return response()->json([
                'success' => true,
                'message' => $doctor->is_active ? 'تم تفعيل الطبيب' : 'تم تعطيل الطبيب',
                'is_active' => $doctor->is_active
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة',
                'error' => $e->getMessage() // للمساعدة في الديباج
            ], 500);
        }
    }

    public function showDetails($id)
    {
        $doctor = Doctor::with(['healthCenter', 'schedules', 'exceptions'])->findOrFail($id);

        $allDays = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة'
        ];

        $upcomingExceptions = $doctor->exceptions()
            ->where('exception_date', '>=', today())
            ->orderBy('exception_date')
            ->get();

        $html = view('health-center.doctors.partials.show', compact('doctor', 'allDays', 'upcomingExceptions'))->render();

        return response()->json(['html' => $html]);
    }

    public function editForm($id)
    {
        $doctor = Doctor::with('schedules')->findOrFail($id);

        $days = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة'
        ];

        $html = view('health-center.doctors.partials.edit', compact('doctor', 'days'))->render();

        return response()->json(['html' => $html]);
    }

    public function update(DoctorUpdateRequest $request, Doctor $doctor)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $doctor, $request) {
                $doctor->update([
                    'name' => $validated['name'],
                    'specialty' => $validated['specialty'],
                    'phone' => $validated['phone'],
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]);

                if (isset($validated['schedules'])) {
                    $doctor->schedules()->delete();

                    foreach ($validated['schedules'] as $schedule) {
                        if (isset($schedule['enabled']) && $schedule['enabled']) {
                            $doctor->schedules()->create([
                                'day_of_week' => $schedule['day_of_week'],
                                'start_time' => $schedule['start_time'],
                                'end_time' => $schedule['end_time'],
                            ]);
                        }
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث البيانات بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحديث'
            ], 500);
        }
    }

    public function destroy(Doctor $doctor)
    {
        try {
            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطبيب بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف'
            ], 500);
        }
    }

    public function getWorkDays(Doctor $doctor)
    {
        $map = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
        ];

        $days = $doctor->schedules()
            ->pluck('day_of_week')
            ->map(fn($day) => $map[strtolower($day)] ?? null)
            ->filter(fn($value) => $value !== null) // فقط إزالة القيم null
            ->values()
            ->toArray();

        return response()->json([
            'days' => $days
        ]);
    }

    public function addException(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'exception_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($doctor) {
                    // التحقق من أن التاريخ في يوم عمل الطبيب
                    $date = Carbon::parse($value);
                    $dayOfWeek = strtolower($date->format('l'));

                    $hasSchedule = $doctor->schedules()
                        ->where('day_of_week', $dayOfWeek)
                        ->exists();

                    if (!$hasSchedule) {
                        $fail('هذا التاريخ ليس ضمن أيام عمل الطبيب');
                    }

                    // التحقق من عدم وجود اعتذار مسبق
                    $existingException = $doctor->exceptions()
                        ->where('exception_date', $date->format('Y-m-d'))
                        ->exists();

                    if ($existingException) {
                        $fail('يوجد اعتذار مسجل مسبقاً لهذا التاريخ');
                    }
                }
            ],
            'reason' => 'nullable|string|max:255',
        ], [
            'exception_date.required' => 'التاريخ مطلوب',
            'exception_date.date' => 'التاريخ غير صحيح',
            'exception_date.after_or_equal' => 'التاريخ يجب أن يكون اليوم أو بعد اليوم',
            'reason.max' => 'السبب يجب ألا يتجاوز 255 حرف',
        ]);

        try {
            $doctor->exceptions()->create([
                'exception_date' => $validated['exception_date'],
                'type' => 'unavailable',
                'reason' => $validated['reason'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الاعتذار بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الاعتذار'
            ], 500);
        }
    }

    public function deleteException(Doctor $doctor, DoctorScheduleException $exception)
    {
        try {
            $exception->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الاعتذار'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الإلغاء'
            ], 500);
        }
    }
}
