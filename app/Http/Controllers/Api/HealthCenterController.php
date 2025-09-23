<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthCenterResource;
use App\Models\HealthCenter;
use Illuminate\Http\Request;

class HealthCenterController extends Controller
{
     public function index(Request $request)
    {
        $query = HealthCenter::with('city.governorate');

        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        return HealthCenterResource::collection($query->get());
    }
}
