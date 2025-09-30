<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Models\HealthCenter;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        
        $drugs = Drug::with(['submittedByCenter', 'approvedBy'])
            ->when($search, function($query, $search) {
                $query->search($search);
            })
            ->when($category, function($query, $category) {
                $query->category($category);
            })
            ->withCount('healthCenters')
            ->paginate(15)
            ->appends(request()->query());
        
        $categories = ['مسكنات', 'مضادات حيوية', 'خافض حرارة', 'فيتامينات', 'أخرى'];
        
        return view('admin.drugs.index', compact('drugs', 'search', 'category', 'categories'));
    }

    public function create()
    {
        $categories = ['مسكنات', 'مضادات حيوية', 'خافض حرارة', 'فيتامينات', 'أخرى'];
        $dosageForms = ['أقراص', 'شراب', 'حقن', 'كبسولات', 'مرهم'];
        
        return view('admin.drugs.create', compact('categories', 'dosageForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'insurance_covered' => 'boolean',
            'category' => 'nullable|string',
            'dosage_form' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_government_approved'] = true;
        $validated['approval_status'] = 'approved';
        $validated['approved_at'] = now();
        $validated['approved_by'] = auth()->id();

        Drug::create($validated);

        return redirect()->route('admin.drugs.index')
            ->with('success', 'تم إضافة الدواء بنجاح');
    }

    public function edit(Drug $drug)
    {
        $categories = ['مسكنات', 'مضادات حيوية', 'خافض حرارة', 'فيتامينات', 'أخرى'];
        $dosageForms = ['أقراص', 'شراب', 'حقن', 'كبسولات', 'مرهم'];
        
        return view('admin.drugs.edit', compact('drug', 'categories', 'dosageForms'));
    }

    public function update(Request $request, Drug $drug)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'insurance_covered' => 'boolean',
            'category' => 'nullable|string',
            'dosage_form' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $drug->update($validated);

        return redirect()->route('admin.drugs.index')
            ->with('success', 'تم تحديث الدواء بنجاح');
    }

    public function destroy(Drug $drug)
    {
        $drug->delete();
        
        return redirect()->route('admin.drugs.index')
            ->with('success', 'تم حذف الدواء بنجاح');
    }

    public function assignToHealthCenter(Request $request, Drug $drug)
    {
        $validated = $request->validate([
            'health_center_id' => 'required|exists:health_centers,id',
            'stock' => 'required|integer|min:0',
        ]);

        $drug->healthCenters()->syncWithoutDetaching([
            $validated['health_center_id'] => [
                'availability' => true,
                'stock' => $validated['stock'],
            ]
        ]);

        return back()->with('success', 'تم توزيع الدواء على الوحدة الصحية');
    }
}