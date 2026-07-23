<?php

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AppointmentCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_cancel_their_own_appointment(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        $doctorProfile = DoctorProfile::factory()->create(['user_id' => $doctor->id]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_profile_id' => $doctorProfile->id,
            'appointment_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '10:30',
            'notes' => 'Test cancellation',
        ]);

        Sanctum::actingAs($patient, ['*']);

        $response = $this->patchJson("/api/appointments/{$appointment->id}/cancel");

        $response->assertStatus(200);
        $response->assertJsonPath('data.status', 'cancelled');
        $response->assertJson([
            'message' => 'Appointment Cancelled Successfully',
        ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_doctor_can_cancel_appointment_for_their_own_profile(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        $doctorProfile = DoctorProfile::factory()->create(['user_id' => $doctor->id]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_profile_id' => $doctorProfile->id,
            'appointment_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '14:30',
            'notes' => 'Doctor cancellation test',
        ]);

        Sanctum::actingAs($doctor, ['*']);

        $response = $this->patchJson("/api/appointments/{$appointment->id}/cancel");

        $response->assertStatus(200);
        $response->assertJsonPath('data.status', 'cancelled');
        $response->assertJson([
            'message' => 'Appointment Cancelled Successfully',
        ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
        ]);
    }
}
