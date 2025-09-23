<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VaccineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'age_range' => "{$this->age_months_min} - {$this->age_months_max} months",
            'doses_required' => $this->doses_required,
            'interval_days' => $this->interval_days,
            'side_effects' => $this->side_effects,
            'precautions' => $this->precautions,
            'is_active' => $this->is_active,
        ];
    }
}

