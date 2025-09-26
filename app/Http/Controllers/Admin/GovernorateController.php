<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernorateRequest;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    //    public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->isAdmin()) {
    //             abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
    //         }
    //         return $next($request);
    //     });
    // }

    public function index(Request $request)
    {
        $query = Governorate::withCount(['cities']);

        // البحث
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $governorates = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // الحفاظ على البحث في الـ pagination
        $governorates->appends($request->all());

        return view('admin.governorates.index', compact('governorates'));
    }

    public function create()
    {
        return view('admin.governorates.create');
    }

    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255|unique:governorates,name'
        ];
        
        $messages = [
            'name.required' => 'اسم المحافظة مطلوب',
            'name.string' => 'اسم المحافظة يجب أن يكون نص',
            'name.max' => 'اسم المحافظة يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم المحافظة موجود بالفعل',
        ];

        // للتعامل مع AJAX requests
        if ($request->ajax()) {
            try {
                $validated = $request->validate($rules, $messages);
                Governorate::create($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة المحافظة بنجاح'
                ]);

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إضافة المحافظة'
                ], 500);
            }
        }

        // للتعامل مع الطلبات العادية
        $validated = $request->validate($rules, $messages);
        Governorate::create($validated);
        
        return redirect()->route('admin.governorates.index')
            ->with('success', 'تم إضافة المحافظة بنجاح');
    }

    public function edit(Governorate $governorate)
    {
        return view('admin.governorates.edit', compact('governorate'));
    }

    public function update(Request $request, Governorate $governorate)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255|unique:governorates,name,' . $governorate->id
        ];
        
        $messages = [
            'name.required' => 'اسم المحافظة مطلوب',
            'name.string' => 'اسم المحافظة يجب أن يكون نص',
            'name.max' => 'اسم المحافظة يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم المحافظة موجود بالفعل',
        ];

        // للتعامل مع AJAX requests
        if ($request->ajax()) {
            try {
                $validated = $request->validate($rules, $messages);
                $governorate->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث المحافظة بنجاح'
                ]);

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث المحافظة'
                ], 500);
            }
        }

        // للتعامل مع الطلبات العادية
        $validated = $request->validate($rules, $messages);
        $governorate->update($validated);
        
        return redirect()->route('admin.governorates.index')
            ->with('success', 'تم تحديث المحافظة بنجاح');
    }

    public function destroy(Governorate $governorate)
    {
        // التحقق من وجود مدن مرتبطة بالمحافظة
        if ($governorate->cities()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف المحافظة لأنها تحتوي على مدن'
                ], 400);
            }
            
            return redirect()->back()
                ->with('error', 'لا يمكن حذف المحافظة لأنها تحتوي على مدن');
        }

        try {
            $governorate->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف المحافظة بنجاح'
                ]);
            }
            
            return redirect()->route('admin.governorates.index')
                ->with('success', 'تم حذف المحافظة بنجاح');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف المحافظة'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المحافظة');
        }
    }
}