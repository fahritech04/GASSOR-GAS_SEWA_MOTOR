<?php

namespace App\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Models\City;

class CityRepository implements CityRepositoryInterface
{
    public function getAllCities()
    {
        return City::withCount('motorcycles')->get();
    }

    public function getCityBySlug($slug)
    {
        return City::where('slug', $slug)->first();
    }
}
