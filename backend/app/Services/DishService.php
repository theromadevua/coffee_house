<?php

namespace App\Services;

use App\Models\Dish;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class DishService
{

    // ====== Rating ======

    /**
     * @param string|null $category
     * @param string $searchParams
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getDishes(?string $category = null, string $searchParams = '', int $perPage = 10): LengthAwarePaginator
    {
        $query = Dish::with('category', 'gallery.images')
            ->withAvg('ratings', 'rating');

        if ($category && $category !== '' && $category !== 'undefined') {
            $query->where('category_id', (int)$category);
        }

        if ($searchParams) {
            $query->where('name', 'LIKE', "%{$searchParams}%");
        }

        $dishes = $query->paginate($perPage);

        $dishes->getCollection()->transform(function ($dish) {
            $dish->isRated = Rating::where('dish_id', $dish->id)
                ->where('user_id', auth('api')->id())
                ->exists();
            return $dish;
        });

        return $dishes;
    }

    /**
     * @param int $id
     * @return Dish
     */
    public function getDishById(int $id): Dish
    {
        $dish = Dish::with('category', 'gallery.images')
            ->withAvg('ratings', 'rating')
            ->find($id);

        if (!$dish) {
            throw new ModelNotFoundException('Dish not found');
        }

        $dish->isRated = Rating::where('dish_id', $id)
            ->where('user_id', auth('api')->id())
            ->exists();

        $dish->rating = Rating::where('dish_id', $id)
            ->where('user_id', auth('api')->id())
            ->value('rating');

        return $dish;
    }

    /**
     * @param array $data
     * @param array|null $images
     * @param string|null $galleryName
     * @return Dish
     */
    public function createDish(array $data, ?array $images = null, ?string $galleryName = null): Dish
    {
        $dish = Dish::create($data);

        $gallery = $dish->gallery()->create([
            'name' => $galleryName ?? 'Gallery for ' . $data['name'],
        ]);

        if ($images) {
            foreach ($images as $image) {
                $path = $this->uploadImage($image);
                $gallery->images()->create([
                    'path' => $path,
                    'caption' => null,
                ]);
            }
        }

        return $dish;
    }

    /**
     * @param $id
     * @param array $data
     * @param array|null $images
     * @param string|null $galleryName
     * @return Dish
     */
    public function updateDish($id, array $data, ?array $images = null, ?string $galleryName = null): Dish
    {

        $dish = Dish::with('category', 'gallery.images')
            ->find($id);

        $dish->update($data);

        $gallery = $dish->gallery;
        if (!$gallery) {
            $gallery = $dish->gallery()->create([
                'name' => $galleryName ?? 'Gallery for ' . $data['name'],
            ]);
        } elseif ($galleryName) {
            $gallery->update(['name' => $galleryName]);
        }
        if ($images) {
            foreach ($gallery->images as $existingImage) {
                Storage::disk('public')->delete($existingImage->path);
                $existingImage->delete();
            }

            foreach ($images as $image) {
                $path = $this->uploadImage($image);
                $gallery->images()->create([
                    'path' => $path,
                    'caption' => null,
                ]);
            }
        }

        return $dish->refresh();
    }

    /**
     * @param $id
     * @return void
     */
    public function deleteDish($id): void
    {
        $dish = $this->getDishById($id);

        if ($dish->image_path) {
            Storage::disk('public')->delete($dish->image_path);
        }

        $dish->delete();
    }

    /**
     * @param string $searchParams
     * @return Collection
     */
    public function search(string $searchParams): Collection
    {
        return Dish::where('name', 'LIKE', "%{$searchParams}%")
            ->with('category')
            ->get();
    }

    /**
     * @param UploadedFile $image
     * @return string
     */
    private function uploadImage(UploadedFile $image): string
    {
        $path = $image->store('images', 'public');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->url($path);
    }

    // ====== Rating ======

    /**
     * @param int $rating
     * @param Dish $dish
     * @return Dish
     */
    public function rateDish(int $rating, Dish $dish): Dish
    {
        Rating::updateOrCreate(
            ['user_id' => auth('api')->id(), 'dish_id' => $dish->id],
            ['rating' => $rating]
        );

        $dish->averageRating();
        $dish->ratingsCount();

        $dish->isRated = true;

        return $dish->loadAvg('ratings', 'rating')->load('gallery.images');
    }
}

