<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'blood_type_id', 'quantity'
    ];

    public function reservationRequest()
    {
        return $this->belongsTo(ReservationRequest::class, 'request_id');
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
} 