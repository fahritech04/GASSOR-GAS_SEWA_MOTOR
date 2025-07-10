<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorcyclePhysicalCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'motorcycle_id',
        'motorbike_rental_id',
        'checklist',
        'video_path',
    ];

    protected $casts = [
        'checklist' => 'array',
    ];

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function motorbikeRental()
    {
        return $this->belongsTo(MotorbikeRental::class);
    }
}
