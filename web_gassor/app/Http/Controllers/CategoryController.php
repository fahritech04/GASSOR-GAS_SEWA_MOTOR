<?php

namespace App\Http\Controllers;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\MotorbikeRentalRepositoryInterface;

class CategoryController extends Controller
{
    private MotorbikeRentalRepositoryInterface $motorbikeRentalRepository;

    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        MotorbikeRentalRepositoryInterface $motorbikeRentalRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->motorbikeRentalRepository = $motorbikeRentalRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function show($slug)
    {
        $motorbikeRentals = $this->motorbikeRentalRepository->getMotorbikeRentalByCategorySlug($slug);
        $motorcycles = $this->motorbikeRentalRepository->getMotorcyclesByCategorySlug($slug);
        $category = $this->categoryRepository->getCategoryBySlug($slug);

        return view('pages.category.show', compact('motorbikeRentals', 'motorcycles', 'category'));
    }
}
