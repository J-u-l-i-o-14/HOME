<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\BloodType;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = BloodType::all();
        $centers = \App\Models\Center::all();
        $centerCount = $centers->count();

        $patients = [
            [
                'first_name' => 'Koffi',
                'last_name' => 'Adote',
                'birthdate' => '1975-11-20',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'A+')->first()->id,
                'phone' => '+228 97 89 01 23',
                'address' => 'Bè, Lomé',
            ],
            [
                'first_name' => 'Akossiwa',
                'last_name' => 'Kouevi',
                'birthdate' => '1982-04-12',
                'gender' => 'female',
                'blood_type_id' => $bloodTypes->where('group', 'O+')->first()->id,
                'phone' => '+228 98 90 12 34',
                'address' => 'Nyékonakpoè, Lomé',
            ],
            [
                'first_name' => 'Togbe',
                'last_name' => 'Kouassi',
                'birthdate' => '1968-09-05',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'B+')->first()->id,
                'phone' => '+228 99 01 23 45',
                'address' => 'Atakpamé, Région des Plateaux',
            ],
            [
                'first_name' => 'Awo',
                'last_name' => 'Mensah',
                'birthdate' => '1990-02-28',
                'gender' => 'female',
                'blood_type_id' => $bloodTypes->where('group', 'AB+')->first()->id,
                'phone' => '+228 90 12 34 56',
                'address' => 'Kpalimé, Région des Plateaux',
            ],
            [
                'first_name' => 'Kossivi',
                'last_name' => 'Agbeko',
                'birthdate' => '1985-07-15',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'A-')->first()->id,
                'phone' => '+228 91 23 45 67',
                'address' => 'Sokodé, Région Centrale',
            ],
            [
                'first_name' => 'Efua',
                'last_name' => 'Dzobo',
                'birthdate' => '1978-12-03',
                'gender' => 'female',
                'blood_type_id' => $bloodTypes->where('group', 'O-')->first()->id,
                'phone' => '+228 92 34 56 78',
                'address' => 'Kara, Région de la Kara',
            ],
            [
                'first_name' => 'Yawo',
                'last_name' => 'Baba',
                'birthdate' => '1983-06-18',
                'gender' => 'male',
                'blood_type_id' => $bloodTypes->where('group', 'B-')->first()->id,
                'phone' => '+228 93 45 67 89',
                'address' => 'Dapaong, Région des Savanes',
            ],
        ];

        foreach ($patients as $i => $patient) {
            $center = $centers[$i % $centerCount];
            $patient['center_id'] = $center->id;
            Patient::create($patient);
        }
    }
}