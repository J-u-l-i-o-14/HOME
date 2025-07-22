<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CenterSchedule;
use App\Models\Center;

class CenterScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $centers = Center::all();
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($centers as $center) {
            foreach ($daysOfWeek as $day) {
                $isOpen = in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
                
                CenterSchedule::create([
                    'center_id' => $center->id,
                    'day_of_week' => $day,
                    'start_time' => $isOpen ? '08:00:00' : '00:00:00',
                    'end_time' => $isOpen ? '18:00:00' : '00:00:00',
                    'max_donors' => 10,
                    'equipements' => null,
                ]);
            }
        }
    }
} 