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
        'category_id',
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
        'stnk' => 'boolean',
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

    public function setStnkImagesAttribute($value)
    {
        $images = is_array($value) ? $value : ($value ? json_decode($value, true) : []);
        $this->attributes['stnk_images'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES) : $value;

        // Otomatis set stnk berdasarkan ketersediaan gambar
        $this->attributes['stnk'] = ! empty($images) && count(array_filter($images)) > 0;
    }

    public function isAvailable()
    {
        return $this->available_stock > 0;
    }

    public function decreaseStock($quantity = 1)
    {
        if ($this->available_stock >= $quantity) {
            $this->available_stock -= $quantity;
            $this->save();

            return true;
        }

        return false;
    }

    public function increaseStock($quantity = 1)
    {
        if (($this->available_stock + $quantity) <= $this->stock) {
            $this->available_stock += $quantity;
            $this->save();

            return true;
        }

        return false;
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_stock', '>', 0);
    }

    public function scopeApprovedOwner($query)
    {
        return $query->whereHas('owner', function ($q) {
            $q->where('is_approved', true);
        });
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(MotorcycleReview::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->reviews()->where('rating', $i)->count();
        }

        return $distribution;
    }

    public function physicalCheck()
    {
        return $this->hasOne(MotorcyclePhysicalCheck::class);
    }
}
