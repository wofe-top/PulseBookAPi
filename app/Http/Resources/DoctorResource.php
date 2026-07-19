<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'bio' => $this->translated_bio,
            'experience_years' => $this->experience_years,
            'consultation_fee' => $this->consultation_fee,
            'specialties' => SpecialtyResource::collection($this->whenLoaded('specialties'))
        ];
    }
}
