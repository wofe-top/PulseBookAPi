<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotAvaliableDoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'start_time' => date('H:i', strtotime($this['start_time'])),
            'end_time'   => date('H:i', strtotime($this['end_time'])),
            'formatted_slot' => date('h:i A', strtotime($this['start_time'])) . ' - ' . date('h:i A', strtotime($this['end_time'])),
        ];
    }
}
