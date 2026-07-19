<?php

namespace App\Services;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;


use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\DB;


use App\Exceptions\BusinessException;

class AuthenticationService
{




    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' =>  $data['role'],
            ]);


            if ($data['role'] == 'doctor') {
                $profile = $user->doctorProfile()->create([
                    'experience_years' => $data['experience_years'],
                    'consultation_fee' => $data['consultation_fee'],
                    'bio'              => $data['bio'] ?? null,
                ]);

                if (!empty($data['doctor_schedule']) && is_array($data['doctor_schedule'])) {

                    foreach ($data['doctor_schedule'] as $s) {
                        DoctorSchedule::create([
                            'doctor_profile_id' => $profile->id,
                            'day_of_week' => $s['day_of_week'],
                            'start_time' => $s['start_time'],
                            'end_time' => $s['end_time'],
                        ]);
                    }
                }
            }




            $token = $user->createToken('pulseBook-AuthToken')->plainTextToken;
            return [
                'user' => $user,
                'token' => $token
            ];
        });
    }


    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {

            throw new BusinessException('The provided credentials are incorrect');
        }


        $token = $user->createToken('WOFE-PlatformToken')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }


    public function logout(User $user): void
    {
        $token =   $user->currentAccessToken();
        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        }
    }
}
