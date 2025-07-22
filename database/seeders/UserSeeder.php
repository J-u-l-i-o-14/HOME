<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Donor;
use App\Models\BloodType;
use App\Models\Center;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get blood types for reference
        $bloodTypes = BloodType::all();
        $centers = Center::all();

        // Un admin et un manager par centre
        foreach ($centers as $center) {
        User::create([
                'name' => 'Admin ' . $center->name,
                'email' => 'admin.' . strtolower(str_replace(' ', '', $center->name)) . '@bloodbank.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
                'center_id' => $center->id,
                'phone' => fake()->phoneNumber(),
                'address' => $center->address,
        ]);
        User::create([
                'name' => 'Manager ' . $center->name,
                'email' => 'manager.' . strtolower(str_replace(' ', '', $center->name)) . '@bloodbank.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
                'center_id' => $center->id,
                'phone' => fake()->phoneNumber(),
                'address' => $center->address,
        ]);
        }

        // Client par défaut
        User::create([
            'name' => 'Client Test',
            'email' => 'client@bloodbank.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0123456791',
            'address' => '789 Boulevard du Client',
        ]);

        // Donneur par défaut
        $firstCenter = $centers->first();
        $donorUser = User::create([
            'name' => 'Marie Dupont',
            'email' => 'donor@bloodbank.com',
            'password' => Hash::make('password'),
            'role' => 'donor',
            'center_id' => $firstCenter->id,
            'phone' => '0123456792',
            'address' => '101 Boulevard des Donneurs',
        ]);

        // Create donor record
        Donor::create([
            'user_id' => $donorUser->id,
            'first_name' => 'Marie',
            'last_name' => 'Dupont',
            'birthdate' => '1990-03-20',
            'gender' => 'female',
            'blood_type_id' => $bloodTypes->where('group', 'B+')->first()->id,
            'last_donation_date' => now()->subDays(60),
            'phone' => '0123456792',
            'email' => 'donor@bloodbank.com',
            'address' => '101 Boulevard des Donneurs',
            'center_id' => $firstCenter->id,
        ]);

        // Générer des donneurs supplémentaires
        $bloodTypeGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders = ['male', 'female'];
        $centerCount = $centers->count();
        for ($i = 1; $i <= 15; $i++) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            $email = fake()->unique()->safeEmail();
            $center = $centers[($i - 1) % $centerCount];
            $donorUser = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'donor',
                'center_id' => $center->id,
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
            ]);
            // Create donor record
            Donor::create([
                'user_id' => $donorUser->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years'),
                'gender' => fake()->randomElement($genders),
                'blood_type_id' => $bloodTypes->where('group', fake()->randomElement($bloodTypeGroups))->first()->id,
                'last_donation_date' => fake()->optional(0.7)->dateTimeBetween('-6 months', '-2 months'),
                'phone' => fake()->phoneNumber(),
                'email' => $email,
                'address' => fake()->address(),
                'center_id' => $center->id,
            ]);
        }

        // Générer des managers supplémentaires
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
            ]);
        }

        // Générer des clients supplémentaires
        for ($i = 1; $i <= 5; $i++) {
            $center = $centers[($i - 1) % $centerCount];
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'client',
                'center_id' => $center->id,
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
            ]);
        }
    }
}