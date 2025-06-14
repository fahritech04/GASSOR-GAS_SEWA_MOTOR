<?php

namespace App\Http\Controllers;

use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\MotorbikeRentalRepositoryInterface;

class CityController extends Controller
{
    private MotorbikeRentalRepositoryInterface $motorbikeRentalRepository;

    private CityRepositoryInterface $cityRepository;

    public function __construct(
        MotorbikeRentalRepositoryInterface $motorbikeRentalRepository,
        CityRepositoryInterface $cityRepository
    ) {
        $this->motorbikeRentalRepository = $motorbikeRentalRepository;
        $this->cityRepository = $cityRepository;
    }

    public function show($slug)
    {
        $motorbikeRentals = $this->motorbikeRentalRepository->getMotorbikeRentalByCitySlug($slug);
        $city = $this->cityRepository->getCityBySlug($slug);

        return view('pages.city.show', compact('motorbikeRentals', 'city'));
    }
}
