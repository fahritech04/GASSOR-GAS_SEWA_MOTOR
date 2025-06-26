<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'name',
        'slug',
    ];

    public function motorbikeRentals()
    {
        return $this->hasMany(MotorbikeRental::class);
    }

    public function motorcycles()
    {
        return $this->hasManyThrough(Motorcycle::class, MotorbikeRental::class);
    }

    public function getMotorcyclesCountAttribute()
    {
        return $this->motorcycles()->count();
    }
}
