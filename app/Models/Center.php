<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'region_id', 'latitude', 'longitude'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bloodBags()
    {
        return $this->hasMany(BloodBag::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function reservationRequests()
    {
        return $this->hasMany(ReservationRequest::class);
    }

    public function stockThresholds()
    {
        return $this->hasMany(StockThreshold::class);
    }

    public function centerSchedules()
    {
        return $this->hasMany(CenterSchedule::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function inventory()
    {
        return $this->hasMany(CenterBloodTypeInventory::class);
    }
} 