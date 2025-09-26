<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('governorate')->withCount(['healthCenters'])->paginate(15);
        $governorates = Governorate::all(); // إضافة المحافظات للنموذج
        return view('admin.cities.index', compact('cities', 'governorates'));
    }

    public function create()
    {
        $governorates = Governorate::all();
        return view('admin.cities.create', compact('governorates'));
    }

    public function store(CityRequest $request)
    {
        City::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المدينة بنجاح'
        ]);
    }

    public function edit(City $city)
    {
        $governorates = Governorate::all();
        return view('admin.cities.edit', compact('city', 'governorates'));
    }

    public function update(CityRequest $request, City $city)
    {
        $city->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المدينة بنجاح'
        ]);
    }

    public function destroy(City $city)
    {
        if ($city->healthCenters()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف المدينة لأنها تحتوي على وحدات صحية'
            ]);
        }

        $city->delete();
      
           return redirect()->route('admin.cities.index')
                ->with('success', 'تم حذف المحافظة بنجاح');
    }

    public function getCitiesByGovernorate(Request $request)
    {
        $cities = City::where('governorate_id', $request->governorate_id)
            ->select('id', 'name')
            ->get();
        return response()->json($cities);
    }
}