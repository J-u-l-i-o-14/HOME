<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'center_id', 'scheduled_at', 'status', 'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function campaign()
    {
        return $this->belongsTo(\App\Models\Campaign::class, 'campaign_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', Carbon::now());
    }

    public function scopeByCenter($query, $centerId)
    {
        return $query->where('center_id', $centerId);
    }

    public function scopeByDonor($query, $donorId)
    {
        return $query->where('donor_id', $donorId);
    }

    // Accessors
    public function getIsUpcomingAttribute()
    {
        return $this->scheduled_at > Carbon::now();
    }

    public function getIsPastAttribute()
    {
        return $this->scheduled_at < Carbon::now();
    }

    public function getIsTodayAttribute()
    {
        return $this->scheduled_at->isToday();
    }

    public function getDaysUntilAppointmentAttribute()
    {
        return Carbon::now()->diffInDays($this->scheduled_at, false);
    }
}