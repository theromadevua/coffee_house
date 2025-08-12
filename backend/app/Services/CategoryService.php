<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{

    // ====== Categories ======

    /**
     * @param string $searchParams
     * @return Collection
     */
    public function getAllCategories(string $searchParams): Collection
    {
        $category = Category::query()
            ->whereHas('dishes'); // Only categories with at least one dish

        if ($searchParams) {
            $category->where('name', 'LIKE', "%{$searchParams}%");
        }

        return $category->get();
    }


    /**
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        $category = Category::create($data);
        return $category;
    }

    /**
     * @param array $data
     * @param int $id
     * @return Category
     */
    public function updateCategory(array $data, int $id): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category->refresh();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteCategory(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
