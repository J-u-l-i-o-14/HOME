<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Center;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $centers = Center::all();
        $centerCount = $centers->count();

        $campaigns = [
            [
                'name' => 'Campagne de Don de Sang - Lomé',
                'description' => 'Campagne de sensibilisation pour le don de sang à Lomé',
                'location' => 'Place de l\'Indépendance, Lomé',
                'date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(16),
            ],
            [
                'name' => 'Don de Sang - Atakpamé',
                'description' => 'Collecte de sang au centre-ville d\'Atakpamé',
                'location' => 'Place du Marché, Atakpamé',
                'date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(21),
            ],
            [
                'name' => 'Sauvez des Vies - Sokodé',
                'description' => 'Campagne de don de sang à Sokodé',
                'location' => 'Place du Marché Central, Sokodé',
                'date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(26),
            ],
            [
                'name' => 'Don de Sang - Kara',
                'description' => 'Collecte de sang à Kara',
                'location' => 'Place de la Mairie, Kara',
                'date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(31),
            ],
            [
                'name' => 'Campagne de Don - Dapaong',
                'description' => 'Collecte de sang à Dapaong',
                'location' => 'Place du Marché, Dapaong',
                'date' => Carbon::now()->addDays(35),
                'end_date' => Carbon::now()->addDays(36),
            ],
        ];

        foreach ($campaigns as $i => $campaign) {
            $center = $centers[$i % $centerCount];
            $campaign['center_id'] = $center->id;
            Campaign::create($campaign);
        }
    }
}