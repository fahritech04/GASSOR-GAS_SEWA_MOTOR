<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorcycleImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'motorcycle_id',
        'image',
    ];

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }
}
