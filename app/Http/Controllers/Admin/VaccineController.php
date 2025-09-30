<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vaccine;
use App\Models\HealthCenter;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    // public function index(Request $request)
    // {
    //     $search = $request->input('search');
        
    //     $vaccines = Vaccine::query()
    //         ->when($search, function($query, $search) {
    //             $query->where('name', 'like', "%{$search}%")
    //                   ->orWhere('description', 'like', "%{$search}%");
    //         })
    //         ->withCount('healthCenters')
    //         ->paginate(15)
    //         ->appends(['search' => $search]); // للحفاظ على البحث في pagination
        
    //     return view('admin.vaccines.index', compact('vaccines', 'search'));
    // }
    public function index(Request $request)
{
    $search = $request->input('search');
    
    $vaccines = Vaccine::search($search)
        ->withCount('healthCenters')
        ->paginate(15)
        ->appends(['search' => $search]);
    
    return view('admin.vaccines.index', compact('vaccines', 'search'));
}

    public function create()
    {
        return view('admin.vaccines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vaccines',
            'description' => 'required|string',
            'age_months_min' => 'required|integer|min:0',
            'age_months_max' => 'required|integer|min:0|gte:age_months_min',
            'doses_required' => 'required|integer|min:1',
            'interval_days' => 'nullable|integer|min:0',
            'side_effects' => 'nullable|string',
            'precautions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Vaccine::create($validated);

        return redirect()->route('admin.vaccines.index')
            ->with('success', 'تم إضافة اللقاح بنجاح');
    }

    public function edit(Vaccine $vaccine)
    {
        return view('admin.vaccines.edit', compact('vaccine'));
    }

    public function update(Request $request, Vaccine $vaccine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vaccines,name,' . $vaccine->id,
            'description' => 'required|string',
            'age_months_min' => 'required|integer|min:0',
            'age_months_max' => 'required|integer|min:0|gte:age_months_min',
            'doses_required' => 'required|integer|min:1',
            'interval_days' => 'nullable|integer|min:0',
            'side_effects' => 'nullable|string',
            'precautions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $vaccine->update($validated);

        return redirect()->route('admin.vaccines.index')
            ->with('success', 'تم تحديث اللقاح بنجاح');
    }

    public function destroy(Vaccine $vaccine)
    {
        $vaccine->delete();
        
        return redirect()->route('admin.vaccines.index')
            ->with('success', 'تم حذف اللقاح بنجاح');
    }

    public function assignToHealthCenter(Request $request, Vaccine $vaccine)
    {
        $validated = $request->validate([
            'assignments' => 'required|array',
            'assignments.*.health_center_id' => 'required|exists:health_centers,id',
            'assignments.*.stock' => 'required|integer|min:0',
        ]);

        foreach ($validated['assignments'] as $assignment) {
            $vaccine->healthCenters()->syncWithoutDetaching([
                $assignment['health_center_id'] => [
                    'availability' => true,
                    'stock' => $assignment['stock']
                ]
            ]);
        }

        return back()->with('success', 'تم توزيع اللقاح بنجاح');
    }
}