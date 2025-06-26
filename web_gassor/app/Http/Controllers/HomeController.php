<?php

namespace App\Http\Controllers;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\MotorbikeRentalRepositoryInterface;

class HomeController extends Controller
{
    private CityRepositoryInterface $cityRepository;

    private CategoryRepositoryInterface $categoryRepository;

    private MotorbikeRentalRepositoryInterface $motorbikeRentalRepository;

    public function __construct(
        CityRepositoryInterface $cityRepository,
        CategoryRepositoryInterface $categoryRepository,
        MotorbikeRentalRepositoryInterface $motorbikeRentalRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->categoryRepository = $categoryRepository;
        $this->motorbikeRentalRepository = $motorbikeRentalRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getAllCategories();
        $popularMotorbikeRentals = $this->motorbikeRentalRepository->getPopularMotorbikeRentals();
        $cities = $this->cityRepository->getAllCities();
        $motorbikeRentals = $this->motorbikeRentalRepository->getAllMotorbikeRentals();
        $motorcycles = $this->motorbikeRentalRepository->getAllMotorcyclesForHome();

        return view('pages.home', compact('categories', 'popularMotorbikeRentals', 'cities', 'motorbikeRentals', 'motorcycles'));
    }
}
