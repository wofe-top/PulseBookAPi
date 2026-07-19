<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', 'in:patient,doctor'],


            'experience_years' => ['required_if:role,doctor', 'integer', 'min:0'],
            'consultation_fee' => ['required_if:role,doctor', 'numeric', 'min:0'],
            'doctor_schedule' => ['required_if:role,doctor', 'array'],
            'bio'              => ['nullable'],
        ];
    }
}
