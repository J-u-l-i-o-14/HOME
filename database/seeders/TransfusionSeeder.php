<?php

namespace Database\Seeders;

use App\Models\Transfusion;
use App\Models\Patient;
use App\Models\BloodBag;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransfusionSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::pluck('id');
        $managers = User::where('role', 'manager')->pluck('id');
        $usedBloodBags = BloodBag::where('status', 'utilisee')->pluck('id');

        // Si pas de managers, utiliser des admins
        if ($managers->isEmpty()) {
            $managers = User::where('role', 'admin')->pluck('id');
        }

        foreach ($usedBloodBags as $bloodBagId) {
            Transfusion::create([
                'patient_id' => fake()->randomElement($patients),
                'blood_bag_id' => $bloodBagId,
                'doctor_id' => fake()->randomElement($managers),
                'transfusion_date' => fake()->dateTimeBetween('-2 months', '-1 week'),
                'volume_transfused' => fake()->numberBetween(200, 450),
                'status' => 'complete',
                'notes' => fake()->optional(0.4)->sentence(),
                'complications' => fake()->optional(0.1)->sentence(),
            ]);
        }
    }
}