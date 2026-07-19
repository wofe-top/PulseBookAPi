<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\DoctorSchedule;
use App\Models\DoctorProfile;
use App\Models\Appointment;
use App\Http\Resources\DoctorResource;
use Carbon\Carbon;

class DoctorService
{
    public function index($filters)
    {
        $doctors =  DoctorProfile::with(['user', 'specialties'])->filter($filters)->paginate(10);

        return DoctorResource::collection($doctors);
    }

    public function calculateAvailableSlots(DoctorProfile $doctorProfile, string $date, int $slotDurationMinutes = 30)
    {
        $dayOfWeek = date('w', strtotime($date));
        $schedule = DoctorSchedule::where('doctor_profile_id', $doctorProfile->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            return [];
        }

        $bookedAppointments = Appointment::where('doctor_profile_id', $doctorProfile->id)
            ->where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);


        $availableSlots = [];

        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        while ($startTime->copy()->addMinutes($slotDurationMinutes)->lte($endTime)) {

            $slotStart = $startTime->format('H:i:s');
            $startTime->addMinutes($slotDurationMinutes);
            $slotEnd = $startTime->format('H:i:s');


            $isOverlap = false;
            foreach ($bookedAppointments as $appointment) {
                if ($slotStart < $appointment->end_time && $slotEnd > $appointment->start_time) {
                    $isOverlap = true;
                    break;
                }
            }


            if (!$isOverlap) {
                $availableSlots[] = [
                    'start_time' => date('H:i', strtotime($slotStart)),
                    'end_time'   => date('H:i', strtotime($slotEnd)),
                ];
            }
        }

        return $availableSlots;
    }
}
