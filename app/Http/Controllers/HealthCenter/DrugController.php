<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Models\HealthCenterDrug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrugController extends Controller
{
    public function drugs()
    {
        $healthCenterId = Auth::user()->health_center_id;

        // إحصائيات عامة
        $stats = [
            'total_drugs' => Drug::filterByHealthCenter($healthCenterId)->count(),
            'available_drugs' => HealthCenterDrug::where('health_center_id', $healthCenterId)
                ->where('availability', true)->count(),
            'out_of_stock' => HealthCenterDrug::where('health_center_id', $healthCenterId)
                ->where('stock', 0)->count(),
            'government_approved' => Drug::filterByHealthCenter($healthCenterId)
                ->governmentApproved()->count()
        ];

        return view('health-center.dashboard', compact('stats'));
    }

    public function index(Request $request)
    {
        $healthCenterId = Auth::user()->health_center_id;

        $query = Drug::filterByHealthCenter($healthCenterId)
            ->with([
                'healthCenters' => function ($q) use ($healthCenterId) {
                    $q->where('health_center_id', $healthCenterId);
                }
            ]);

        // فلترة حسب الكاتيجوري
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // فلترة حسب التوفر
        if ($request->filled('availability')) {
            $query->whereHas('healthCenters', function ($q) use ($healthCenterId, $request) {
                $q->where('health_center_id', $healthCenterId)
                    ->where('availability', $request->availability == '1');
            });
        }

        // بحث بالاسم
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('scientific_name', 'like', '%' . $request->search . '%');
            });
        }

        $drugs = $query->paginate(15);
        $categories = Drug::filterByHealthCenter($healthCenterId)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('health-center.drugs.index', compact('drugs', 'categories'));
    }

    public function show($id)
    {
        $healthCenterId = Auth::user()->health_center_id;

        $drug = Drug::filterByHealthCenter($healthCenterId)
            ->with([
                'healthCenters' => function ($q) use ($healthCenterId) {
                    $q->where('health_center_id', $healthCenterId);
                }
            ])
            ->findOrFail($id);

        return view('health-center.drugs.show', compact('drug'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $healthCenterId = Auth::user()->health_center_id;

        HealthCenterDrug::where('health_center_id', $healthCenterId)
            ->where('drug_id', $id)
            ->update(['stock' => $request->stock]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الكمية بنجاح'
        ]);
    }

    public function toggle($id)
    {
        $healthCenterId = Auth::user()->health_center_id;

        $healthCenterDrug = HealthCenterDrug::where('health_center_id', $healthCenterId)
            ->where('drug_id', $id)
            ->firstOrFail();

        $healthCenterDrug->update([
            'availability' => !$healthCenterDrug->availability
        ]);

        return response()->json([
            'success' => true,
            'availability' => $healthCenterDrug->availability,
            'message' => $healthCenterDrug->availability ? 'الدواء متاح الآن' : 'الدواء غير متاح'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'drug_id' => 'required|exists:drugs,id',
            'stock' => 'required|integer|min:0',
            'availability' => 'boolean'
        ]);

        $healthCenterId = Auth::user()->health_center_id;

        // التأكد من أن الدواء غير مضاف مسبقاً
        $exists = HealthCenterDrug::where('health_center_id', $healthCenterId)
            ->where('drug_id', $request->drug_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الدواء مضاف بالفعل لمركزك'
            ]);
        }

        HealthCenterDrug::create([
            'health_center_id' => $healthCenterId,
            'drug_id' => $request->drug_id,
            'stock' => $request->stock,
            'availability' => $request->availability ?? true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الدواء بنجاح'
        ]);
    }

    public function destroy($id)
    {
        $healthCenterId = Auth::user()->health_center_id;

        HealthCenterDrug::where('health_center_id', $healthCenterId)
            ->where('drug_id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الدواء من مركزك'
        ]);
    }

    public function available()
    {
        $healthCenterId = Auth::user()->health_center_id;

        // الأدوية المعتمدة من الحكومة والغير مضافة للمركز
        $drugs = Drug::governmentApproved()
            ->whereDoesntHave('healthCenters', function ($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId);
            })
            ->select('id', 'name', 'scientific_name', 'category')
            ->get();

        return response()->json($drugs);
    }

    // ============= إدارة الأدوية الجديدة =============

    public function create()
    {
        return view('health-center.drugs.create');
    }

    public function submitNewDrug(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:drugs,name',
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0|max:999999.99',
            'insurance_covered' => 'boolean',
            'category' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:255',
            'initial_stock' => 'required|integer|min:0'
        ]);

        $healthCenterId = Auth::user()->health_center_id;

        // إنشاء الدواء الجديد
        $drug = Drug::create([
            'name' => $request->name,
            'scientific_name' => $request->scientific_name,
            'description' => $request->description,
            'manufacturer' => $request->manufacturer,
            'price' => $request->price,
            'insurance_covered' => $request->has('insurance_covered'),
            'category' => $request->category,
            'dosage_form' => $request->dosage_form,
            'approval_status' => 'pending',
            'submitted_by_center_id' => $healthCenterId,
            'is_government_approved' => false,
            'is_active' => true
        ]);

        // إضافة الدواء لمركز المقدم
        HealthCenterDrug::create([
            'health_center_id' => $healthCenterId,
            'drug_id' => $drug->id,
            'stock' => $request->initial_stock,
            'availability' => false // غير متاح حتى الموافقة
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب الدواء الجديد بنجاح، في انتظار موافقة الحكومة'
        ]);
    }

    public function pendingDrugs()
    {
        $healthCenterId = Auth::user()->health_center_id;

        $pendingDrugs = Drug::where('submitted_by_center_id', $healthCenterId)
            ->where('approval_status', 'pending')
            ->with([
                'healthCenters' => function ($q) use ($healthCenterId) {
                    $q->where('health_center_id', $healthCenterId);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('health-center.drugs.pending', compact('pendingDrugs'));
    }

    public function rejectedDrugs()
    {
        $healthCenterId = Auth::user()->health_center_id;

        $rejectedDrugs = Drug::where('submitted_by_center_id', $healthCenterId)
            ->where('approval_status', 'rejected')
            ->with([
                'healthCenters' => function ($q) use ($healthCenterId) {
                    $q->where('health_center_id', $healthCenterId);
                }
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('health-center.drugs.rejected', compact('rejectedDrugs'));
    }

    public function resubmit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0|max:999999.99',
            'insurance_covered' => 'boolean',
            'category' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:255'
        ]);

        $healthCenterId = Auth::user()->health_center_id;

        $drug = Drug::where('submitted_by_center_id', $healthCenterId)
            ->where('approval_status', 'rejected')
            ->findOrFail($id);

        // التحقق من عدم تكرار الاسم
        $existingDrug = Drug::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();

        if ($existingDrug) {
            return response()->json([
                'success' => false,
                'message' => 'اسم الدواء موجود بالفعل'
            ]);
        }

        $drug->update([
            'name' => $request->name,
            'scientific_name' => $request->scientific_name,
            'description' => $request->description,
            'manufacturer' => $request->manufacturer,
            'price' => $request->price,
            'insurance_covered' => $request->has('insurance_covered'),
            'category' => $request->category,
            'dosage_form' => $request->dosage_form,
            'approval_status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة إرسال طلب الدواء بنجاح'
        ]);
    }
}
