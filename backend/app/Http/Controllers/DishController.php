<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateDishRequest;
use App\Http\Requests\SearchDishRequest;
use App\Http\Requests\StoreDishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Models\Dish;
use App\Services\DishService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DishController extends Controller
{
    use ApiResponse;

    protected $dishService;

    public function __construct(
        DishService $dishService
    )
    {
        $this->dishService = $dishService;
    }

    // ====== Dishes ======

    /**
     * Retrieve a paginated list of dishes with optional category and search filters.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $category = $request->query('category');
        $searchParams = $request->query('searchParams', '');
        $perPage = $request->query('per_page', 8);
        $dishes = $this->dishService->getDishes($category, $searchParams, $perPage);

        return $this->successResponse(
            $dishes,
            'Dishes successfully retrieved',
            Response::HTTP_OK
        );
    }

    /**
     * Create a new dish.
     * @param StoreDishRequest $request
     * @return JsonResponse
     */
    public function store(StoreDishRequest $request): JsonResponse
    {
        $data = $request->validated();
        $images = $request->file('images');
        $galleryName = $request->input('gallery_name');
        $dish = $this->dishService->createDish($data, $images, $galleryName);

        return $this->successResponse(
            $dish->load('gallery.images'),
            'The dish is successfully created',
            Response::HTTP_CREATED
        );
    }

    /**
     * Update an existing dish.
     * @param UpdateDishRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateDishRequest $request, int $id): JsonResponse
    {
        $data = $request->only(['name', 'description', 'price', 'category_id']);
        $images = $request->file('images', []);
        $galleryName = $request->input('gallery_name');
        $dish = $this->dishService->updateDish($id, $data, $images, $galleryName);

        return $this->successResponse(
            $dish->load('gallery.images'),
            'The dish is successfully updated',
            Response::HTTP_OK
        );
    }

    /**
     * Retrieve a specific dish by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $dish = $this->dishService->getDishById($id);

        return $this->successResponse(
            $dish,
            'The dish is successfully retrieved',
            Response::HTTP_OK
        );
    }

    /**
     * Search for dishes based on search parameters.
     * @param SearchDishRequest $request
     * @return JsonResponse
     */
    public function search(SearchDishRequest $request): JsonResponse
    {
        $searchParams = $request->query('searchParams', '');
        $dishes = $this->dishService->search($searchParams);

        return $this->successResponse(
            $dishes,
            'Dishes successfully retrieved',
            Response::HTTP_OK
        );
    }

    /**
     * Delete a dish by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->dishService->deleteDish($id);

        return $this->successResponse(
            null,
            'The dish is successfully deleted',
            Response::HTTP_NO_CONTENT
        );
    }

    // ====== Rating ======

    /**
     * Rate a dish.
     * @param RateDishRequest $request
     * @return JsonResponse
     */
    public function rateDish(RateDishRequest $request, Dish $dish): JsonResponse
    {
        $rating = $request->validated()['rating'];
        $ratedDish = $this->dishService->rateDish($rating, $dish);
        return $this->successResponse(
            $ratedDish,
            'Dishes successfully rated',
            Response::HTTP_OK
        );
    }
}
