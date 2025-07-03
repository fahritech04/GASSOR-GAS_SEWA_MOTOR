<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motorcycle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'motorbike_rental_id',
        'name',
        'motorcycle_type',
        'vehicle_number_plate',
        'stnk',
        'stnk_images',
        'price_per_day',
        'stock',
        'available_stock',
        'has_gps',
        'start_rent_hour',
        'end_rent_hour',
    ];

    protected $casts = [
        'stnk_images' => 'array',
        'has_gps' => 'boolean',
        'start_rent_hour' => 'string',
        'end_rent_hour' => 'string',
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
        $this->attributes['stnk_images'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES) : $value;
    }

    /**
     * Cek apakah motor tersedia
     */
    public function isAvailable()
    {
        return $this->available_stock > 0;
    }

    /**
     * Mengurangi stok yang tersedia
     */
    public function decreaseStock($quantity = 1)
    {
        if ($this->available_stock >= $quantity) {
            $this->available_stock -= $quantity;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Menambah stok yang tersedia
     */
    public function increaseStock($quantity = 1)
    {
        if (($this->available_stock + $quantity) <= $this->stock) {
            $this->available_stock += $quantity;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Scope untuk motor yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_stock', '>', 0);
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
