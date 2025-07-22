<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockThreshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'blood_type_id', 'warning_threshold', 'critical_threshold'
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
} 