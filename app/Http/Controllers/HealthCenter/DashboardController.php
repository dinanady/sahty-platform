<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * عرض الداشبورد الرئيسي للمركز الصحي
     */
    public function index()
    {
        $user = auth()->user();

        // التأكد من أن المستخدم له health_center_id
        if (!$user->health_center_id) {
            abort(403, 'لا يوجد مركز صحي مرتبط بحسابك');
        }

        $healthCenterId = $user->health_center_id;
        $healthCenter = $user->healthCenter;

        // 1. الإحصائيات الرئيسية
        $stats = $this->getMainStats($healthCenterId);

        // 2. إحصائيات المواعيد حسب الحالة
        $appointmentStats = $this->getAppointmentStats($healthCenterId);

        // 3. المواعيد القادمة اليوم
        $todayAppointments = Appointment::where('health_center_id', $healthCenterId)
            ->whereDate('appointment_date', Carbon::today())
            ->where('status', 'مجدول')
            ->with(['vaccine'])
            ->orderBy('appointment_time')
            ->take(10)
            ->get();

        // 4. الأطباء النشطين مجمعين حسب التخصص
        $activeDoctors = Doctor::where('health_center_id', $healthCenterId)
            ->where('is_active', true)
            ->with(['schedules' => function ($q) {
                $q->where('available', true);
            }])
            ->get()
            ->groupBy('specialty'); // تجميع حسب التخصص

        // 5. اللقاحات المتوفرة
        $availableVaccines = Vaccine::whereHas('healthCenters', function ($q) use ($healthCenterId) {
            $q->where('health_center_id', $healthCenterId)
              ->where('health_center_vaccine.availability', true);
        })
        ->where('is_active', true)
        ->get();

        // 6. الأدوية منخفضة المخزون (أقل من 10)
        $lowStockDrugs = Drug::whereHas('healthCenters', function ($q) use ($healthCenterId) {
            $q->where('health_center_id', $healthCenterId)
              ->where('health_center_drugs.stock', '<', 10);
        })
        ->with(['healthCenters' => function ($q) use ($healthCenterId) {
            $q->where('health_center_id', $healthCenterId);
        }])
        ->get();

        // 7. إحصائيات الأسبوع الحالي (آخر 7 أيام)
        $weeklyStats = $this->getWeeklyStats($healthCenterId);

        // 8. أكثر اللقاحات طلباً (آخر 30 يوم)
        $topVaccines = $this->getTopVaccines($healthCenterId);

        return view('health-center.dashboard', compact(
            'healthCenter',
            'stats',
            'appointmentStats',
            'todayAppointments',
            'activeDoctors',
            'availableVaccines',
            'lowStockDrugs',
            'weeklyStats',
            'topVaccines'
        ));
    }

    /**
     * الإحصائيات الرئيسية
     */
    private function getMainStats($healthCenterId)
    {
        return [
            // عدد الأطباء
            'total_doctors' => Doctor::where('health_center_id', $healthCenterId)->count(),
            'active_doctors' => Doctor::where('health_center_id', $healthCenterId)
                ->where('is_active', true)
                ->count(),

            // عدد المواعيد
            'total_appointments' => Appointment::where('health_center_id', $healthCenterId)->count(),

            // عدد اللقاحات
            'total_vaccines' => Vaccine::whereHas('healthCenters', function ($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId);
            })->count(),
            'available_vaccines' => Vaccine::whereHas('healthCenters', function ($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId)
                  ->where('health_center_vaccine.availability', true);
            })->count(),

            // عدد الأدوية
            'total_drugs' => Drug::whereHas('healthCenters', function ($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId);
            })->count(),
            'available_drugs' => Drug::whereHas('healthCenters', function ($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId)
                  ->where('health_center_drugs.availability', true);
            })->count(),
        ];
    }

    /**
     * إحصائيات المواعيد حسب الحالة
     */
    private function getAppointmentStats($healthCenterId)
    {
        $today = Carbon::today();

        return [
            'scheduled' => Appointment::where('health_center_id', $healthCenterId)
                ->where('status', 'مجدول')
                ->count(),
            'completed' => Appointment::where('health_center_id', $healthCenterId)
                ->where('status', 'مكتمل')
                ->count(),
            'cancelled' => Appointment::where('health_center_id', $healthCenterId)
                ->where('status', 'ملغي')
                ->count(),
            'no_show' => Appointment::where('health_center_id', $healthCenterId)
                ->where('status', 'لم يحضر')
                ->count(),
            'today_scheduled' => Appointment::where('health_center_id', $healthCenterId)
                ->whereDate('appointment_date', $today)
                ->where('status', 'مجدول')
                ->count(),
            'this_week' => Appointment::where('health_center_id', $healthCenterId)
                ->whereBetween('appointment_date', [
                    $today->copy()->startOfWeek(),
                    $today->copy()->endOfWeek()
                ])
                ->count(),
            'this_month' => Appointment::where('health_center_id', $healthCenterId)
                ->whereMonth('appointment_date', $today->month)
                ->whereYear('appointment_date', $today->year)
                ->count(),
        ];
    }

    /**
     * إحصائيات الأسبوع الحالي (آخر 7 أيام)
     */
    private function getWeeklyStats($healthCenterId)
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dailyAppointments = Appointment::where('health_center_id', $healthCenterId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "مكتمل" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "مجدول" THEN 1 ELSE 0 END) as scheduled')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ملء الأيام الناقصة بقيم صفرية
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $dates[$date] = [
                'date' => $date,
                'total' => 0,
                'completed' => 0,
                'scheduled' => 0
            ];
        }

        foreach ($dailyAppointments as $stat) {
            $dates[$stat->date] = [
                'date' => $stat->date,
                'total' => $stat->total,
                'completed' => $stat->completed,
                'scheduled' => $stat->scheduled
            ];
        }

        return array_values($dates);
    }

    /**
     * أكثر اللقاحات طلباً (آخر 30 يوم)
     */
    private function getTopVaccines($healthCenterId)
    {
        $startDate = Carbon::now()->subDays(30);

        return Appointment::where('health_center_id', $healthCenterId)
            ->where('appointment_date', '>=', $startDate)
            ->select('vaccine_id', DB::raw('COUNT(*) as total'))
            ->with('vaccine')
            ->groupBy('vaccine_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }
}
