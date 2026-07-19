<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Filters\AppointmentFilter;
use App\Http\Resources\AppointmentResource;

use App\Services\AppointmentService;
use App\Http\Requests\Api\StoreAppointmentRequest;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{


    public function __construct(
        protected AppointmentService $appointmentService
    ) {}





    public function index(AppointmentFilter  $filters)
    {
        $appointments = $this->appointmentService->index($filters);

        return AppointmentResource::collection($appointments)->additional([
            'message' => 'Appointments Fetched Successfully'
        ]);
    }


    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->store($request->validated());





        return (new AppointmentResource($appointment))->additional([
            'message' => 'Appointment Created Successfully'
        ]);
    }
}
