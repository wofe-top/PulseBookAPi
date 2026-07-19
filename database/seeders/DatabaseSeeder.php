<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(SpecialtySeeder::class);

        User::factory(10)->create(['role' => 'patient']);



        DoctorProfile::factory(5)->create()->each(function ($doctor) {
            $specialties = Specialty::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $doctor->specialties()->attach($specialties);
        });
    }
}
