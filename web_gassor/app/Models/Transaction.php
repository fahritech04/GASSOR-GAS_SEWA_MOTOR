<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'motorbike_rental_id',
        'motorcycle_id',
        'name',
        'email',
        'phone_number',
        'payment_method',
        'payment_status',
        'rental_status',
        'start_date',
        'start_time',
        'duration',
        'total_amount',
        'transaction_date',
        'end_time',
        'snap_url',
        'status',
    ];

    public function motorbikeRental()
    {
        return $this->belongsTo(MotorbikeRental::class);
    }

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }
}
