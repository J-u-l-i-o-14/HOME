<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'user_id', 'action', 'notes'
    ];

    public function reservationRequest()
    {
        return $this->belongsTo(ReservationRequest::class, 'reservation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 