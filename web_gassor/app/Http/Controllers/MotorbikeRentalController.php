<?php

namespace App\Http\Controllers;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\MotorbikeRentalRepositoryInterface;
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
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalForDisplayBySlug($slug);

        return view('pages.motorbike-rental.show', compact('motorbikeRental'));
    }

    public function showMotorcycle($id)
    {
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($id);

        if (! $motorcycle) {
            abort(404);
        }

        $motorcycle->load(['motorbikeRental.city', 'category', 'images']);

        return view('pages.motorbike-rental.motorcycle-detail', compact('motorcycle'));
    }

    // public function motorcycles($slug)
    // {
    //     $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);

    //     return view('pages.motorbike-rental.motorcycles', compact('motorbikeRental'));
    // }

    public function motorcycles($slug)
    {
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalAvailableBySlug($slug);

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
        $motorcycles = $this->motorbikeRentalRepository->getAllMotorcycles($request->search, $request->city, $request->category);

        return view('pages.motorbike-rental.index', compact('motorcycles'));
    }
}
