<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DrugResource;
use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        $drugs = Drug::filterByHealthCenter($request->health_center_id)->get();

        return DrugResource::collection($drugs);
    }

}
