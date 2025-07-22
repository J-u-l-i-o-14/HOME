<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_bag_id', 'patient_id', 'transfusion_date', 'volume_used', 'notes'
    ];

    protected $casts = [
        'transfusion_date' => 'datetime',
        'volume_used' => 'decimal:2',
    ];

    public function bloodBag()
    {
        return $this->belongsTo(BloodBag::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scopes
    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByBloodBag($query, $bloodBagId)
    {
        return $query->where('blood_bag_id', $bloodBagId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('transfusion_date', now()->toDateString());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transfusion_date', now()->month)
                    ->whereYear('transfusion_date', now()->year);
    }
}