<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CenterBloodTypeInventory;
use App\Models\Center;
use App\Models\BloodType;

class CenterBloodTypeInventorySeeder extends Seeder
{
    public function run(): void
    {
        $centers = Center::all();
        $bloodTypes = BloodType::all();

        foreach ($centers as $center) {
            foreach ($bloodTypes as $bloodType) {
                CenterBloodTypeInventory::create([
                    'center_id' => $center->id,
                    'blood_type_id' => $bloodType->id,
                    'available_quantity' => 0,
                    'reserved_quantity' => 0,
                ]);
            }
        }
    }
} 