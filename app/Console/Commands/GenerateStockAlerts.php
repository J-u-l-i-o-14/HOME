<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Center;
use App\Models\BloodType;
use App\Models\BloodBag;
use App\Models\CenterBloodTypeInventory;
use App\Models\Alert;
use Carbon\Carbon;

class GenerateStockAlerts extends Command
{
    protected $signature = 'blood:generate-alerts';
    protected $description = 'Génère des alertes d\'expiration et de seuil pour chaque centre';

    public function handle()
    {
        $this->info('Génération des alertes de stock et d\'expiration...');
        $now = Carbon::now();
        foreach (Center::all() as $center) {
            foreach (BloodType::all() as $type) {
                // 1. Alerte expiration (poches expirant dans 7 jours)
                $expiringCount = BloodBag::where('center_id', $center->id)
                    ->where('blood_type_id', $type->id)
                    ->where('status', 'available')
                    ->whereBetween('expires_at', [$now, $now->copy()->addDays(7)])
                    ->count();
                if ($expiringCount > 0) {
                    Alert::updateOrCreate([
                        'center_id' => $center->id,
                        'blood_type_id' => $type->id,
                        'type' => 'expiration',
                        'resolved' => false,
                    ], [
                        'message' => $expiringCount . ' poche(s) de sang du groupe ' . $type->group . ' vont expirer dans 7 jours ou moins.',
                    ]);
                }
                // 2. Alerte seuil
                $inventory = CenterBloodTypeInventory::where('center_id', $center->id)
                    ->where('blood_type_id', $type->id)
                    ->first();
                $available = $inventory ? $inventory->available_quantity : 0;
                $threshold = 5; // seuil d'alerte par défaut
                if ($available > 0 && $available <= $threshold) {
                    Alert::updateOrCreate([
                        'center_id' => $center->id,
                        'blood_type_id' => $type->id,
                        'type' => 'low_stock',
                        'resolved' => false,
                    ], [
                        'message' => 'Stock faible pour le groupe ' . $type->group . ' : ' . $available . ' poche(s) restante(s).',
                    ]);
                }
            }
        }
        $this->info('Alertes générées.');

        // Résoudre automatiquement les alertes qui ne sont plus d'actualité
        foreach (Alert::where('resolved', false)->get() as $alert) {
            if ($alert->type === 'low_stock') {
                $inventory = CenterBloodTypeInventory::where('center_id', $alert->center_id)
                    ->where('blood_type_id', $alert->blood_type_id)
                    ->first();
                $available = $inventory ? $inventory->available_quantity : 0;
                $threshold = 5;
                if ($available > $threshold) {
                    $alert->resolved = true;
                    $alert->save();
                }
            }
            if ($alert->type === 'expiration') {
                $now = now();
                $expiringCount = BloodBag::where('center_id', $alert->center_id)
                    ->where('blood_type_id', $alert->blood_type_id)
                    ->where('status', 'available')
                    ->whereBetween('expires_at', [$now, $now->copy()->addDays(7)])
                    ->count();
                if ($expiringCount === 0) {
                    $alert->resolved = true;
                    $alert->save();
                }
            }
        }
        return 0;
    }
} 