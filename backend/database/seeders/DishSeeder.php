<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $mainDishes = Category::where('name', 'Main Dishes')->first();
        $desserts = Category::where('name', 'Desserts')->first();

        // Create Grilled Chicken dish with gallery
        $chickenDish = Dish::create([
            'category_id' => $mainDishes->id,
            'name' => 'Grilled Chicken',
            'description' => 'Juicy grilled chicken breast with herbs.',
            'price' => 12,
        ]);

        $chickenGallery = $chickenDish->gallery()->create([
            'name' => 'Grilled Chicken Gallery',
        ]);
        Image::factory()
            ->count(2)
            ->create([
                'imageable_id' => $chickenGallery->id,
                'imageable_type' => Gallery::class,
            ]);

        // Create Chocolate Cake dish with gallery
        $cakeDish = Dish::create([
            'category_id' => $desserts->id,
            'name' => 'Chocolate Cake',
            'description' => 'Rich chocolate cake with creamy frosting.',
            'price' => 6,
        ]);

        $cakeGallery = $cakeDish->gallery()->create([
            'name' => 'Chocolate Cake Gallery',
        ]);
        Image::factory()
            ->count(2)
            ->create([
                'imageable_id' => $cakeGallery->id,
                'imageable_type' => Gallery::class,
            ]);

        // Create 8 additional dishes with galleries
        Dish::factory(8)->create()->each(function ($dish) {
            $gallery = $dish->gallery()->create([
                'name' => "Gallery for {$dish->name}",
            ]);
            Image::factory()
                ->count(rand(1, 3))
                ->create([
                    'imageable_id' => $gallery->id,
                    'imageable_type' => Gallery::class,
                ]);
        });
    }
}