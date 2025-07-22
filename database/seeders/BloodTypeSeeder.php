<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodType;

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = [
            ['group' => 'A+'],
            ['group' => 'A-'],
            ['group' => 'B+'],
            ['group' => 'B-'],
            ['group' => 'AB+'],
            ['group' => 'AB-'],
            ['group' => 'O+'],
            ['group' => 'O-'],
        ];

        foreach ($bloodTypes as $bloodType) {
            BloodType::create($bloodType);
        }
    }
} 