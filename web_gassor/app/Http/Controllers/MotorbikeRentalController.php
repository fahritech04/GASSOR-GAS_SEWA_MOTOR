<?php

namespace App\Http\Controllers;

use App\Interfaces\MotorbikeRentalRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use Illuminate\Http\Request;

class MotorbikeRentalController extends Controller
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

    public function show($slug)
    {
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);

        return view('pages.motorbike-rental.show', compact('motorbikeRental'));
    }

    public function motorcycles($slug)
    {
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);

        return view('pages.motorbike-rental.motorcycles', compact('motorbikeRental'));
    }

    public function find()
    {

        $categories = $this->categoryRepository->getAllCategories();
        $cities = $this->cityRepository->getAllCities();

        return view('pages.motorbike-rental.find', compact('categories', 'cities'));
    }

    public function findResults(Request $request)
    {
        $motorbikeRentals = $this->motorbikeRentalRepository->getAllMotorbikeRentals($request->search, $request->city, $request->category);

        return view('pages.motorbike-rental.index', compact('motorbikeRentals'));
    }
}
