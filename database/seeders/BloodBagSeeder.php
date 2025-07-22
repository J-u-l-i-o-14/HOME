<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodBag;
use App\Models\BloodType;
use App\Models\Center;
use App\Models\Donor;
use Carbon\Carbon;

class BloodBagSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        $donors = Donor::all();

        for ($i = 1; $i <= 50; $i++) {
            $bloodType = $bloodTypes->random();
            $center = $centers->random();
            $donor = $donors->random();
            
            // Date de collecte aléatoire dans les 30 derniers jours
            $collectedAt = Carbon::now()->subDays(rand(1, 30));
            $expiresAt = $collectedAt->copy()->addDays(42);

            BloodBag::create([
                'blood_type_id' => $bloodType->id,
                'center_id' => $center->id,
                'donor_id' => $donor->id,
                'volume' => rand(300, 450),
                'collected_at' => $collectedAt,
                'expires_at' => $expiresAt,
                'status' => $expiresAt->isPast() ? 'expired' : 'available',
            ]);
        }
    }
}