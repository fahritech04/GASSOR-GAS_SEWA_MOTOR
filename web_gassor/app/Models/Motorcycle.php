<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motorcycle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'motorbike_rental_id',
        'name',
        'motorcycle_type',
        'vehicle_number_plate',
        'capacity',
        'price_per_day',
        'is_available',
    ];

    public function motorbikeRental()
    {
        return $this->belongsTo(MotorbikeRental::class);
    }

    public function images()
    {
        return $this->hasMany(MotorcycleImage::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
