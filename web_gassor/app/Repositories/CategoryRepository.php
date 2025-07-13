<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories()
    {
        return Category::withCount(['motorcycles' => function ($query) {
            $query->where('available_stock', '>', 0)->approvedOwner();
        }])->get();
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->first();
    }
}
