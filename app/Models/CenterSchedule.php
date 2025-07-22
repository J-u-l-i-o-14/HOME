<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'day_of_week', 'start_time', 'end_time', 'max_donors', 'equipements'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'equipements' => 'array',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    // Scopes
    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeByCenter($query, $centerId)
    {
        return $query->where('center_id', $centerId);
    }
} 