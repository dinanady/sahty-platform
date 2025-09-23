<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'child_name' => $this->child_name,
            'child_birth_date' => $this->child_birth_date,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'status' => $this->status,
            'dose_number' => $this->dose_number,
            'notes' => $this->notes,
            'vaccine' => new VaccineResource($this->whenLoaded('vaccine')),
            'health_center' => new HealthCenterResource($this->whenLoaded('healthCenter')),
        ];
    }
}
