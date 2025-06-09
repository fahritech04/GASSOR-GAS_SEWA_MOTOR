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
        'stnk',
        'stnk_images',
        'price_per_day',
        'is_available',
    ];

    protected $casts = [
        'stnk_images' => 'array',
    ];

    /**
     * Accessor untuk mendapatkan array gambar STNK (depan & belakang)
     */
    public function getStnkImagesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Mutator untuk menyimpan array gambar STNK (depan & belakang)
     */
    public function setStnkImagesAttribute($value)
    {
        $this->attributes['stnk_images'] = json_encode($value);
    }

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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
