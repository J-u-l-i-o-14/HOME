<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Center;
use App\Models\BloodType;
use App\Models\BloodBag;
use App\Models\CenterBloodTypeInventory;

class SyncBloodInventory extends Command
{
    protected $signature = 'blood:sync-inventory';
    protected $description = 'Synchronise l\'inventaire des stocks de sang avec les poches réellement disponibles';

    public function handle()
    {
        $this->info('Synchronisation de l\'inventaire des stocks de sang...');
        $total = 0;
        foreach (Center::all() as $center) {
            foreach (BloodType::all() as $type) {
                $count = BloodBag::where('center_id', $center->id)
                    ->where('blood_type_id', $type->id)
                    ->where('status', 'available')
                    ->count();
                CenterBloodTypeInventory::updateOrCreate(
                    [
                        'center_id' => $center->id,
                        'blood_type_id' => $type->id,
                    ],
                    [
                        'available_quantity' => $count,
                    ]
                );
                $total += $count;
            }
        }
        $this->info('Inventaire synchronisé. Total poches disponibles : ' . $total);
        return 0;
    }
} 