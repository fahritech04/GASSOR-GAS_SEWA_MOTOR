<?php

namespace App\Http\Controllers;

use App\Interfaces\MotorbikeRentalRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

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
        $category = $this->categoryRepository->getCategoryBySlug($slug);

        return view('pages.category.show', compact('motorbikeRentals', 'category'));
    }
}
