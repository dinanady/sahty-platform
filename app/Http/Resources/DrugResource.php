<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrugResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'scientific_name' => $this->scientific_name,
            'description' => $this->description,
            'manufacturer' => $this->manufacturer,
            'price' => $this->price,
            'insurance_covered' => $this->insurance_covered,
            'category' => $this->category,
            'dosage_form' => $this->dosage_form,
            'is_active' => $this->is_active,
            'pivot' => $this->when($this->healthCenters->isNotEmpty(), function () {
                // الـ pivot بيكون على الـ healthCenter الأول
                $healthCenter = $this->healthCenters->first();
                return [
                    'availability' => $healthCenter->pivot->availability,
                    'stock' => $healthCenter->pivot->stock,
                ];
            }),

        ];

    }
}

