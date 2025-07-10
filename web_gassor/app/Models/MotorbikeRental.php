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
        'description',
        'price',
        'address',
        'contact',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
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
            'motorbike_rental_id',
            'id',
            'id',
            'owner_id'
        )->distinct();
    }

    public function motorcyclesWithGps()
    {
        return $this->hasMany(Motorcycle::class)->where('has_gps', true);
    }

    public function category()
    {
        return $this->hasOneThrough(Category::class, Motorcycle::class, 'motorbike_rental_id', 'id', 'id', 'category_id');
    }

    // kategori dominan atau "Campuran"
    public function getPredominantCategory()
    {
        $categories = $this->motorcycles()
            ->with('category')
            ->get()
            ->pluck('category')
            ->filter()
            ->unique('id');

        if ($categories->count() === 0) {
            return null;
        }

        if ($categories->count() === 1) {
            return $categories->first();
        }

        // Beberapa kategori
        $categoryCounts = $this->motorcycles()
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category,
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count');

        return $categoryCounts->first()['category'] ?? null;
    }

    // Periksa apakah memiliki sepeda motor dari beberapa kategori
    public function hasMultipleCategories()
    {
        return $this->motorcycles()
            ->distinct('category_id')
            ->count('category_id') > 1;
    }

    public function physicalChecks()
    {
        return $this->hasMany(MotorcyclePhysicalCheck::class);
    }
}
