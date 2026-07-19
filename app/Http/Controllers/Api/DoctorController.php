<?php

namespace App\Http\Controllers\Api;

use App\Models\DoctorProfile;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\SlotAvaliableDoctorResource;
use Illuminate\Http\Request;

use App\Filters\DoctorProfileFilter;

use App\Services\DoctorService;

use App\Http\Requests\Api\DoctorRequest;


class DoctorController extends Controller
{


    public function __construct(
        protected DoctorService  $doctorService
    ) {}


    /**
 *
 *
 * @authenticated
 */
    public function index(DoctorProfileFilter $filters)
    {

        $result =  $this->doctorService->index($filters);
        return $result->additional([
            'message' => 'Doctors Fetched Successfully'
        ]);
    }



    /**
 *
 *
 * @authenticated
 */
    public function getAvailableSlots(DoctorRequest $request, DoctorProfile $doctorProfile)
    {



        $slots = $this->doctorService->calculateAvailableSlots(
            $doctorProfile,
            $request->validated('date')
        );



        return SlotAvaliableDoctorResource::collection($slots)->additional([
            'message' => 'Slots Fetched Successfully'
        ]);
    }
}
