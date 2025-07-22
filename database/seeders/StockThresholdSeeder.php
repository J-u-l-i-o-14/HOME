<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockThreshold;
use App\Models\Center;
use App\Models\BloodType;

class StockThresholdSeeder extends Seeder
{
    public function run(): void
    {
        $centers = Center::all();
        $bloodTypes = BloodType::all();

        foreach ($centers as $center) {
            foreach ($bloodTypes as $bloodType) {
                StockThreshold::create([
                    'center_id' => $center->id,
                    'blood_type_id' => $bloodType->id,
                    'warning_threshold' => 10,
                    'critical_threshold' => 5,
                ]);
            }
        }
    }
} 