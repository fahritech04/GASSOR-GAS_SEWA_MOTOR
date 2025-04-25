<?php

namespace App\Repositories;

use App\Interfaces\MotorbikeRentalRepositoryInterface;
use App\Models\MotorbikeRental;
use App\Models\Motorcycle;
use Illuminate\Database\Eloquent\Builder;

class MotorbikeRentalRepository implements MotorbikeRentalRepositoryInterface
{
    public function getAllMotorbikeRentals($search = null, $city = null, $category = null)
    {
        $query = MotorbikeRental::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($city) {
            $query->whereHas('city', function (Builder $query) use ($city) {
                $query->where('slug', $city);
            });
        }

        if ($category) {
            $query->whereHas('category', function (Builder $query) use ($category) {
                $query->where('slug', $category);
            });
        }

        return $query->get();
    }

    public function getPopularMotorbikeRentals($limit = 5)
    {
        return MotorbikeRental::withCount('transactions')->orderBy('transactions_count', 'desc')->take($limit)->get();
    }

    public function getMotorbikeRentalByCategorySlug($slug)
    {
        return MotorbikeRental::whereHas('category', function (Builder $query) use ($slug) {
            $query->where('slug', $slug);
        })->get();
    }

    public function getMotorbikeRentalByCitySlug($slug)
    {
        return MotorbikeRental::whereHas('city', function (Builder $query) use ($slug) {
            $query->where('slug', $slug);
        })->get();
    }

    public function getMotorbikeRentalBySlug($slug)
    {
        return MotorbikeRental::where('slug', $slug)->first();
    }

    public function getMotorbikeRentalMotorcycleById($id)
    {
        return Motorcycle::find($id);
    }
}
