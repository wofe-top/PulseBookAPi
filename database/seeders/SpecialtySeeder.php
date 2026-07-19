<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            ['en' => 'Cardiology', 'ar' => 'أمراض القلب'],
            ['en' => 'Pediatrics', 'ar' => 'طب الأطفال'],
            ['en' => 'Dermatology', 'ar' => 'أمراض الجلدية'],
            ['en' => 'Orthopedics', 'ar' => 'جراحة العظام'],
            ['en' => 'Neurology', 'ar' => 'أمراض الأعصاب'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create(['name' => $specialty]);
        }
    }
}
