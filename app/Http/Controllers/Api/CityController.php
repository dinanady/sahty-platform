<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('governorate');

        if ($request->has('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        return CityResource::collection($query->get());
    }
}
