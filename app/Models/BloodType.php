<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    use HasFactory;

    protected $fillable = ['group'];

    public function donors()
    {
        return $this->hasMany(Donor::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function bloodBags()
    {
        return $this->hasMany(BloodBag::class);
    }

    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function stockThresholds()
    {
        return $this->hasMany(StockThreshold::class);
    }

    public function inventory()
    {
        return $this->hasMany(CenterBloodTypeInventory::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
} 