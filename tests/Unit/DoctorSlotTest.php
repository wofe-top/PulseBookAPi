<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Services\DoctorService;

class DoctorSlotTest extends TestCase
{

    public function test_it_calculates_slots_correctly_without_appointments(): void
    {

        $service = new DoctorService();
        $startTime = '09:00:00';
        $endTime = '11:00:00';
        $bookedAppointments = [];


        $slots = $service->getAvailableSlotsFromRawData($startTime, $endTime, $bookedAppointments, 30);



        $this->assertCount(4, $slots);

        $this->assertEquals('09:00', $slots[0]['start_time']);
        $this->assertEquals('09:30', $slots[0]['end_time']);
    }

    public function test_it_excludes_overlapped_booked_slots(): void
    {
        $service = new DoctorService();
        $startTime = '09:00:00';
        $endTime = '11:00:00';
        $bookedAppointments = [
            ['start_time' => '09:30:00', 'end_time' => '10:00:00']
        ];

        $slots = $service->getAvailableSlotsFromRawData($startTime, $endTime, $bookedAppointments, 30);

        $this->assertCount(3, $slots);

        $this->assertEquals('09:00', $slots[0]['start_time']);
        $this->assertEquals('10:00', $slots[1]['start_time']);
    }
}
