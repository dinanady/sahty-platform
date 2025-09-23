<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthCenterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'working_hours' => $this->working_hours,
            'is_active' => $this->is_active,
            'governorate' => new GovernorateResource($this->whenLoaded('governorate')),
            'city' => new CityResource($this->whenLoaded('city')),
            'doctors' => DoctorResource::collection($this->whenLoaded('doctors')),
            'drugs' => DrugResource::collection($this->whenLoaded('drugs')),
        ];
    }
}
