<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use ApiResponse;

    protected $categoryService;

    public function __construct(
        CategoryService $categoryService
    )
    {
        $this->categoryService = $categoryService;
    }

    // ====== Categories ======

    /**
     * Retrieve a list of all categories.
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategories(Request $request): JsonResponse
    {
        $searchParams = $request->query('searchParams', '');
        $categories = $this->categoryService->getAllCategories($searchParams);
        return $this->successResponse(
            $categories,
            'Successful categories request',
            Response::HTTP_OK
        );
    }

    /**
     * Get a category by ID.
     * @param Category $category
     * @return JsonResponse
     */
    public function getCategoryById(Category $category): JsonResponse
    {
        if (!$category) {
            return $this->errorResponse(
                'Category not found',
                Response::HTTP_NOT_FOUND
            );
        }
        return $this->successResponse(
            $category,
            'Successful category request',
            Response::HTTP_OK
        );
    }

    /**
     * Create a new category.
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function createCategory(CategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());
        return $this->successResponse(
            $category,
            'Successful category creation',
            Response::HTTP_CREATED
        );
    }

    /**
     * Update an existing category.
     * @param CategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateCategory(CategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryService->updateCategory($request->validated(), $id);
        return $this->successResponse(
            $category,
            'Successful category update',
            Response::HTTP_OK
        );
    }

    /**
     * Delete a category by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function deleteCategory(int $id): JsonResponse
    {
        $this->categoryService->deleteCategory($id);
        return $this->successResponse(
            null,
            'Category successfully deleted',
            Response::HTTP_OK
        );
    }
}
