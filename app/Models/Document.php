<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'path', 'verified', 'verified_by', 'verified_at'
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function reservationRequest()
    {
        return $this->belongsTo(ReservationRequest::class, 'reservation_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
} 