<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'birthdate', 'gender',
        'blood_type_id', 'last_donation_date', 'phone', 'email', 'address', 'center_id'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'last_donation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function bloodBags()
    {
        return $this->hasMany(BloodBag::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function donationHistories()
    {
        return $this->hasMany(DonationHistory::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getCanDonateAttribute()
    {
        if (!$this->last_donation_date) {
            return true;
        }
        
        // Un donneur peut donner du sang tous les 56 jours (8 semaines)
        return $this->last_donation_date->addDays(56) <= now();
    }

    public function getNextDonationDateAttribute()
    {
        if (!$this->last_donation_date) {
            return now();
        }
        
        return $this->last_donation_date->addDays(56);
    }
} 