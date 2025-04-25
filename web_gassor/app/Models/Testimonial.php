<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'motorbike_rental_id',
        'photo',
        'name',
        'content',
        'rating',
    ];

    public function motorbikeRental()
    {
        return $this->belongsTo(MotorbikeRental::class);
    }
}
