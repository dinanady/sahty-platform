<?php

namespace App\Http\Controllers\HealthCenter;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Models\HealthCenterDrug;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DrugController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('hc-view-drugs'), only: ['index', 'show', 'available']),
            new Middleware(PermissionMiddleware::using('hc-create-drugs'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('hc-edit-drugs'), only: ['update']),
            new Middleware(PermissionMiddleware::using('hc-delete-drugs'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('hc-toggle-drug-status'), only: ['toggle']),
            new Middleware(PermissionMiddleware::using('hc-submit-new-drug'), only: ['submitNewDrug', 'pendingDrugs']),
            new Middleware(PermissionMiddleware::using('hc-resubmit-drug'), only: ['resubmit', 'rejectedDrugs']),
        ];
    }
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

        // ناخد الفلاتر من الريكوست
        $filters = $request->only(['search', 'category', 'availability']);
        $perPage = $request->get('per_page', 15); 
        $drugs = Drug::where(function ($q) use ($healthCenterId) {
                $q->whereHas('healthCenters', function ($subQ) use ($healthCenterId) {
                    $subQ->where('submitted_by_center_id', $healthCenterId);
                })
                    ->orWhere(function ($subQ) {
                    $subQ->where('is_government_approved', true)
                        ->whereNull('submitted_by_center_id');
                });
            })
            ->applyFilters($filters)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $categories = Drug::filterByHealthCenter($healthCenterId)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'drugs' => [
                    'html' => view('health-center.drugs.partials.table-rows', compact('drugs'))->render(),
                    'current_page' => $drugs->currentPage(),
                    'per_page' => $drugs->perPage(),
                    'total' => $drugs->total(),
                    'count' => $drugs->count()
                ],
                'pagination' => $drugs->appends($request->all())->links()->render()
            ]);
        }

        return view('health-center.drugs.index', compact('drugs', 'categories', 'filters'));
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
        try {
            $request->validate([
                'stock' => 'required|integer|min:0'
            ]);

            $healthCenterId = Auth::user()->health_center_id;

            $updated = HealthCenterDrug::where('health_center_id', $healthCenterId)
                ->where('drug_id', $id)
                ->update(['stock' => $request->stock]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على الدواء في مركزك'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating drug stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الكمية'
            ], 500);
        }
    }

    public function toggle($id)
    {
        try {
            $healthCenterId = Auth::user()->health_center_id;

            $healthCenterDrug = HealthCenterDrug::where('health_center_id', $healthCenterId)
                ->where('drug_id', $id)
                ->first();

            if (!$healthCenterDrug) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على الدواء في مركزك'
                ], 404);
            }

            $healthCenterDrug->update([
                'availability' => !$healthCenterDrug->availability
            ]);

            return response()->json([
                'success' => true,
                'availability' => $healthCenterDrug->availability,
                'message' => $healthCenterDrug->availability ? 'الدواء متاح الآن' : 'الدواء غير متاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling drug availability: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة التوفر'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
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
                ], 422);
            }

            // التأكد من أن الدواء معتمد حكومياً
            $drug = Drug::where('id', $request->drug_id)
                ->where('is_government_approved', true)
                ->first();

            if (!$drug) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن إضافة هذا الدواء لأنه غير معتمد حكومياً'
                ], 422);
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات المدخلة غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding drug to health center: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الدواء'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $healthCenterId = Auth::user()->health_center_id;

            $deleted = HealthCenterDrug::where('health_center_id', $healthCenterId)
                ->where('drug_id', $id)
                ->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على الدواء في مركزك'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الدواء من مركزك بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing drug from health center: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الدواء'
            ], 500);
        }
    }

    public function available()
    {
        try {
            $healthCenterId = Auth::user()->health_center_id;

            // الأدوية المعتمدة من الحكومة والغير مضافة للمركز
            $drugs = Drug::where('is_government_approved', true)
                ->where('is_active', true)
                ->whereDoesntHave('healthCenters', function ($q) use ($healthCenterId) {
                    $q->where('health_center_id', $healthCenterId);
                })
                ->select('id', 'name', 'scientific_name', 'category')
                ->orderBy('name')
                ->get();

            return response()->json($drugs);

        } catch (\Exception $e) {
            Log::error('Error fetching available drugs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل الأدوية المتاحة'
            ], 500);
        }
    }

    // ============= إدارة الأدوية الجديدة =============

    public function create()
    {
        return view('health-center.drugs.create');
    }

    public function submitNewDrug(Request $request)
    {
        try {
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

            DB::beginTransaction();

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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال طلب الدواء الجديد بنجاح، في انتظار موافقة الحكومة'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'البيانات المدخلة غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting new drug: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال طلب الدواء'
            ], 500);
        }
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
        try {
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
                ], 422);
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات المدخلة غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error resubmitting drug: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة إرسال طلب الدواء'
            ], 500);
        }
    }
}
