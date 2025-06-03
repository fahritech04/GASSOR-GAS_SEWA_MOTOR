<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorbikeRental extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'city_id',
        'category_id',
        'description',
        'price',
        'address',
        'contact',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function motorcycles()
    {
        return $this->hasMany(Motorcycle::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function owners()
    {
        return $this->hasManyThrough(
            \App\Models\User::class,
            \App\Models\Motorcycle::class,
            'motorbike_rental_id', // Foreign key motorcycles
            'id', // Foreign key users
            'id', // Local key rentals
            'owner_id' // Local key motorcycles
        )->distinct();
    }
}
