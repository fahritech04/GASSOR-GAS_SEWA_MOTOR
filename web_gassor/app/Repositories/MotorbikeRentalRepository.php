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
            $query->whereHas('motorcycles', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            });
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

    // public function getMotorbikeRentalBySlug($slug)
    // {
    //     return MotorbikeRental::where('slug', $slug)->first();
    // }

    // public function getMotorbikeRentalBySlug($slug)
    // {
    //     return MotorbikeRental::where('slug', $slug)
    //         ->with(['motorcycles' => function ($query) {
    //             $query->where('is_available', true);
    //         }])
    //         ->first();
    // }

    public function getMotorbikeRentalBySlug($slug)
    {
        return MotorbikeRental::with([
            'motorcycles.images',
            'city', 'category', 'bonuses',
        ])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getMotorbikeRentalMotorcycleById($id)
    {
        return Motorcycle::find($id);
    }

    public function getMotorbikeRentalAvailableBySlug($slug)
    {
        return MotorbikeRental::with([
            'motorcycles' => function ($q) {
                $q->where('available_stock', '>', 0);
            },
            'motorcycles.images', 'city', 'category',
        ])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getAllMotorcycles($search = null, $city = null, $category = null)
    {
        $query = Motorcycle::with(['motorbikeRental.city', 'motorbikeRental.category', 'images']);

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        if ($city) {
            $query->whereHas('motorbikeRental.city', function (Builder $query) use ($city) {
                $query->where('slug', $city);
            });
        }

        if ($category) {
            $query->whereHas('motorbikeRental.category', function (Builder $query) use ($category) {
                $query->where('slug', $category);
            });
        }

        return $query->get();
    }
}
