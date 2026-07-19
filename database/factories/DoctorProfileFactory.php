<?php

namespace Database\Factories;

use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorProfile>
 */
class DoctorProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['role' => 'doctor']),
            'bio' => [
                'en' => fake()->paragraph(),
                'ar' => 'طبيب متخصص ولديه خبرة واسعة في تقديم الرعاية الطبية الممتازة للمرضى.',
            ],
            'experience_years' => fake()->numberBetween(2, 25),
            'consultation_fee' => fake()->randomFloat(2, 50, 100),
        ];
    }
}
