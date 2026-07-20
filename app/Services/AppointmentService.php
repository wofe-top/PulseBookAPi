<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AppointmentResource;

use App\Models\DoctorSchedule;
use App\Exceptions\BusinessException;


use App\Interfaces\NotificationServiceInterface;

class AppointmentService
{

    protected $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }




    public function index($filters)
    {

        $appointments =  Appointment::with(['patient', 'doctorProfile'])->filter($filters)->paginate(10);

        return $appointments;
    }


    public function store(array $data): Appointment
    {

        $dayOfWeek = date('w', strtotime($data['appointment_date']));



        $isAvailable = DoctorSchedule::where('doctor_profile_id', $data['doctor_profile_id'])
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $data['start_time'])
            ->where('end_time', '>=', $data['end_time'])
            ->exists();


        if (!$isAvailable) {
            throw new BusinessException(__('The doctor does not work during these hours or on this day.'));
        }


        $hasOverlap = Appointment::where('doctor_id', $data['doctor_profile_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($data) {
                $query->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time']);
            })
            ->exists();

        if ($hasOverlap) {
            throw new BusinessException(__('This time slot is already booked for this doctor.'));
        }


        $this->notificationService->sendNotification($data['patient_id'], 'You have a new appointment');



        return   DB::transaction(function () use ($data) {
            return Appointment::create([
                'patient_id' => $data['patient_id'],
                'doctor_id' => $data['doctor_profile_id'],
                'appointment_date' => $data['appointment_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }
}
