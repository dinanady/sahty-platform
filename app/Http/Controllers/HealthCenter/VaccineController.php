<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vaccine;
use App\Models\HealthCenter;
use Illuminate\Support\Facades\Auth;

class VaccineController extends Controller
{
    // عرض اللقاحات الخاصة بالمركز الصحي
    public function index()
    {
        $user = Auth::user();
        $healthCenter = HealthCenter::find($user->health_center_id);

        if (!$healthCenter) {
            return redirect()->back()->with('error', 'لا يوجد مركز صحي مرتبط بحسابك');
        }

        // جلب جميع اللقاحات المتاحة من الدولة
        $allVaccines = Vaccine::where('is_active', true)->get();

        // جلب اللقاحات المضافة للمركز الصحي
        $centerVaccines = $healthCenter->vaccines()->withPivot('availability', 'created_at')->get();

        return view('health-center.vaccines.index', compact('allVaccines', 'centerVaccines', 'healthCenter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vaccine_id' => 'required|exists:vaccines,id',
            'availability' => 'boolean'
        ]);

        $user = Auth::user();
        $healthCenter = HealthCenter::find($user->health_center_id);

        if (!$healthCenter) {
            return response()->json(['error' => 'لا يوجد مركز صحي مرتبط بحسابك'], 403);
        }

        // التحقق من أن اللقاح غير مضاف مسبقاً
        $exists = $healthCenter->vaccines()->where('vaccine_id', $request->vaccine_id)->exists();

        if ($exists) {
            return response()->json(['error' => 'هذا اللقاح مضاف مسبقاً لمركزكم'], 400);
        }

        $healthCenter->vaccines()->attach($request->vaccine_id, [
            'availability' => $request->availability ?? true
        ]);

        $vaccine = Vaccine::find($request->vaccine_id);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة اللقاح بنجاح',
            'vaccine' => $vaccine
        ]);
    }

    public function updateAvailability(Request $request, $vaccineId)
    {
        $request->validate([
            'availability' => 'required|boolean'
        ]);

        $user = Auth::user();
        $healthCenter = HealthCenter::find($user->health_center_id);

        if (!$healthCenter) {
            return response()->json(['error' => 'لا يوجد مركز صحي مرتبط بحسابك'], 403);
        }

        $updated = $healthCenter->vaccines()->updateExistingPivot($vaccineId, [
            'availability' => $request->availability
        ]);

        if (!$updated) {
            return response()->json(['error' => 'اللقاح غير موجود في مركزكم'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة اللقاح بنجاح'
        ]);
    }

    public function destroy($vaccineId)
    {
        $user = Auth::user();
        $healthCenter = HealthCenter::find($user->health_center_id);

        if (!$healthCenter) {
            return response()->json(['error' => 'لا يوجد مركز صحي مرتبط بحسابك'], 403);
        }

        // التحقق من عدم وجود مواعيد مرتبطة بهذا اللقاح
        $hasAppointments = $healthCenter->appointments()
            ->where('vaccine_id', $vaccineId)
            ->whereIn('status', ['مجدول'])
            ->exists();

        if ($hasAppointments) {
            return response()->json([
                'error' => 'لا يمكن إزالة هذا اللقاح لوجود مواعيد مجدولة له'
            ], 400);
        }

        $healthCenter->vaccines()->detach($vaccineId);

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة اللقاح من مركزكم بنجاح'
        ]);
    }

    public function show($id)
    {
        $vaccine = Vaccine::findOrFail($id);
        return response()->json($vaccine);
    }

    public function getVaccinesByAge(Request $request)
    {
        $childAge = $request->child_age; // بالأشهر
        $healthCenterId = $request->health_center_id;

        $query = Vaccine::filterByAge($childAge)
            ->where('is_active', true);

        if ($healthCenterId) {
            $query->whereHas('healthCenters', function($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId)
                  ->where('availability', true);
            });
        }

        $vaccines = $query->get();

        return response()->json($vaccines);
    }

    public function getAvailableVaccines($healthCenterId)
    {
        $healthCenter = HealthCenter::findOrFail($healthCenterId);

        $vaccines = $healthCenter->vaccines()
            ->where('availability', true)
            ->where('is_active', true)
            ->get();

        return response()->json($vaccines);
    }
}
