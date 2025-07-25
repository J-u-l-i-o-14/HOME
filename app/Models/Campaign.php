<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'name', 'description', 'location', 'date', 'end_date', 'status'
    ];

    protected $casts = [
        'date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function donationHistories()
    {
        return $this->hasMany(DonationHistory::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', Carbon::now());
    }

    public function scopeByCenter($query, $centerId)
    {
        return $query->where('center_id', $centerId);
    }

    // Statut publication
    public function publish() {
        $this->update(['status' => 'published']);
    }
    public function archive() {
        $this->update(['status' => 'archived']);
    }
    public function isPublished() {
        return $this->status === 'published';
    }
    public function isArchived() {
        return $this->status === 'archived';
    }
    public function isDraft() {
        return $this->status === 'draft';
    }

    public function getIsUpcomingAttribute()
    {
        return $this->date > Carbon::now();
    }

    public function getIsPastAttribute()
    {
        return $this->date < Carbon::now();
    }
}