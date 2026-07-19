<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\DoctorProfile;
use App\Http\Resources\DoctorResource;


class DoctorService
{
    public function index($filters)
    {
        $doctors =  DoctorProfile::with(['user', 'specialties'])->filter($filters)->paginate(10);

        return DoctorResource::collection($doctors);
    }
}
