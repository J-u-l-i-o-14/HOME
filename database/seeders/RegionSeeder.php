<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Région Maritime'],
            ['name' => 'Région des Plateaux'],
            ['name' => 'Région Centrale'],
            ['name' => 'Région de la Kara'],
            ['name' => 'Région des Savanes'],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
} 