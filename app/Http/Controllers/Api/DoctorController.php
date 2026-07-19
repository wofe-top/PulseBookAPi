<?php

namespace App\Http\Controllers\Api;

use App\Models\DoctorProfile;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use Illuminate\Http\Request;

use App\Filters\DoctorProfileFilter;

use App\Services\DoctorService;


class DoctorController extends Controller
{


    public function __construct(
        protected DoctorService  $doctorService
    ) {}
    public function index(DoctorProfileFilter $filters)
    {

        $result =  $this->doctorService->index($filters);
        return $result->additional([
            'message' => 'Doctors Fetched Successfully'
        ]);
    }
}
