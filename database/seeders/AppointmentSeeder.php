<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Donor;
use App\Models\Center;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $donors = Donor::all();
        $centers = Center::all();

        // Rendez-vous à venir
        for ($i = 1; $i <= 15; $i++) {
            Appointment::create([
                'donor_id' => $donors->random()->id,
                'center_id' => $centers->random()->id,
                'scheduled_at' => fake()->dateTimeBetween('now', '+2 months'),
                'status' => fake()->randomElement(['pending', 'confirmed']),
                'notes' => fake()->optional(0.3)->sentence(),
            ]);
        }

        // Quelques rendez-vous passés
        for ($i = 1; $i <= 10; $i++) {
            Appointment::create([
                'donor_id' => $donors->random()->id,
                'center_id' => $centers->random()->id,
                'scheduled_at' => fake()->dateTimeBetween('-2 months', '-1 week'),
                'status' => fake()->randomElement(['completed', 'cancelled']),
                'notes' => fake()->optional(0.3)->sentence(),
            ]);
        }
    }
}