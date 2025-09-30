<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->specialty,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'schedules' => DoctorScheduleResource::collection($this->schedules),
            'today_schedule' => $this->isAvailableOn(now()->toDateString()),
        ];
    }
}

