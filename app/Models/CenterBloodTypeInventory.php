<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterBloodTypeInventory extends Model
{
    use HasFactory;

    protected $table = 'center_blood_type_inventory';

    protected $fillable = [
        'center_id', 'blood_type_id', 'available_quantity', 'reserved_quantity'
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    // Scopes
    public function scopeByCenter($query, $centerId)
    {
        return $query->where('center_id', $centerId);
    }

    public function scopeByBloodType($query, $bloodTypeId)
    {
        return $query->where('blood_type_id', $bloodTypeId);
    }

    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('available_quantity', '<=', $threshold);
    }

    public function scopeCriticalStock($query, $threshold = 3)
    {
        return $query->where('available_quantity', '<=', $threshold);
    }
} 