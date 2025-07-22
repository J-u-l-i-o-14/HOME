<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;
use App\Models\User;
use App\Models\BloodType;
use App\Models\Center;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = BloodType::all();
        $centers = Center::all();

        $donors = [
            [
                'first_name' => 'Kossi',
                'last_name' => 'Adzogble',
                'email' => 'kossi.adzogble@example.com',
                'phone' => '+228 90 12 34 56',
                'birthdate' => '1990-05-15',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'A+')->first()->id,
                'address' => 'Tokoin Doumassessé, Lomé',
                'last_donation_date' => Carbon::now()->subMonths(3),
            ],
            [
                'first_name' => 'Afi',
                'last_name' => 'Mensah',
                'email' => 'afi.mensah@example.com',
                'phone' => '+228 91 23 45 67',
                'birthdate' => '1985-08-22',
                'gender' => 'female',
                'blood_type_id' => $bloodTypes->where('group', 'O+')->first()->id,
                'address' => 'Agoè, Lomé',
                'last_donation_date' => Carbon::now()->subMonths(6),
            ],
            [
                'first_name' => 'Komlan',
                'last_name' => 'Tchakounte',
                'email' => 'komlan.tchakounte@example.com',
                'phone' => '+228 92 34 56 78',
                'birthdate' => '1992-12-10',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'B+')->first()->id,
                'address' => 'Atakpamé, Région des Plateaux',
                'last_donation_date' => Carbon::now()->subMonths(2),
            ],
            [
                'first_name' => 'Abra',
                'last_name' => 'Kouassi',
                'email' => 'abra.kouassi@example.com',
                'phone' => '+228 93 45 67 89',
                'birthdate' => '1988-03-18',
                'gender' => 'female',
                'blood_type_id' => $bloodTypes->where('group', 'AB+')->first()->id,
                'address' => 'Kpalimé, Région des Plateaux',
                'last_donation_date' => Carbon::now()->subMonths(4),
            ],
            [
                'first_name' => 'Yawo',
                'last_name' => 'Agbeko',
                'email' => 'yawo.agbeko@example.com',
                'phone' => '+228 94 56 78 90',
                'birthdate' => '1995-07-25',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'A-')->first()->id,
                'address' => 'Sokodé, Région Centrale',
                'last_donation_date' => Carbon::now()->subMonths(1),
            ],
            [
                'first_name' => 'Efua',
                'last_name' => 'Dzobo',
                'email' => 'efua.dzobo@example.com',
                'phone' => '+228 95 67 89 01',
                'birthdate' => '1987-11-30',
                'gender' => 'female',
                'blood_type_id' => $bloodTypes->where('group', 'O-')->first()->id,
                'address' => 'Kara, Région de la Kara',
                'last_donation_date' => Carbon::now()->subMonths(5),
            ],
            [
                'first_name' => 'Kodjo',
                'last_name' => 'Baba',
                'email' => 'kodjo.baba@example.com',
                'phone' => '+228 96 78 90 12',
                'birthdate' => '1993-04-12',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'B-')->first()->id,
                'address' => 'Dapaong, Région des Savanes',
                'last_donation_date' => Carbon::now()->subMonths(2),
            ],
        ];

        $centerCount = $centers->count();
        foreach ($donors as $i => $donorData) {
            $center = $centers[$i % $centerCount];
            // Create user account for donor
            $user = User::create([
                'name' => $donorData['first_name'] . ' ' . $donorData['last_name'],
                'email' => $donorData['email'],
                'password' => Hash::make('password'),
                'role' => 'donor',
                'phone' => $donorData['phone'],
                'address' => $donorData['address'],
                'center_id' => $center->id,
            ]);

            // Create donor record linked to user
            Donor::create([
                'user_id' => $user->id,
                'first_name' => $donorData['first_name'],
                'last_name' => $donorData['last_name'],
                'birthdate' => $donorData['birthdate'],
                'gender' => $donorData['gender'],
                'blood_type_id' => $donorData['blood_type_id'],
                'last_donation_date' => $donorData['last_donation_date'],
                'phone' => $donorData['phone'],
                'email' => $donorData['email'],
                'address' => $donorData['address'],
                'center_id' => $center->id,
            ]);
        }
    }
} 