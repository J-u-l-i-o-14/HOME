<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationBloodBag extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'blood_bag_id'
    ];

    public function reservationRequest()
    {
        return $this->belongsTo(ReservationRequest::class, 'reservation_id');
    }

    public function bloodBag()
    {
        return $this->belongsTo(BloodBag::class);
    }
} 