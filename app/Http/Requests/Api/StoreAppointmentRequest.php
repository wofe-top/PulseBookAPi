<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'doctor_profile_id' => ['required', 'exists:users,id'],
            'patient_id' => ['required', 'exists:users,id'],
            'appointment_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.exists'         => __('The selected doctor does not exist.'),
            'appointment_date.after'   => __('The appointment date must be a future date.'),
        ];
    }
}
