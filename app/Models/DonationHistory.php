<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'campaign_id', 'blood_bag_id', 'donated_at', 'volume', 'notes'
    ];

    protected $casts = [
        'donated_at' => 'datetime',
        'volume' => 'decimal:2',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function bloodBag()
    {
        return $this->belongsTo(BloodBag::class);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('donated_at', now()->month)
                     ->whereYear('donated_at', now()->year);
    }
} 