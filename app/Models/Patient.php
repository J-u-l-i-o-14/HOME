<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'birthdate', 'gender',
        'blood_type_id', 'phone', 'address', 'center_id'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function transfusions()
    {
        return $this->hasMany(Transfusion::class);
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
}