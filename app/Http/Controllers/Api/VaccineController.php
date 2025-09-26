<?php

namespace App\Http\Controllers\Api;

use App\Filters\Website\VaccineFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\VaccineResource;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    public function index(VaccineFilter $filter)
    {
        $vaccines = Vaccine::filter($filter);

        return VaccineResource::collection($vaccines);
    }
}
