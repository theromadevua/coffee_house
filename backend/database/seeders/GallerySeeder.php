<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Get all dishes
        $dishes = Dish::all();

        foreach ($dishes as $dish) {
            // Create a gallery for each dish
            $gallery = Gallery::create([
                'name' => "Gallery for {$dish->name}",
            ]);

            $dish->gallery()->save($gallery);

            Image::factory()
                ->count(rand(1, 3))
                ->forGallery()
                ->create([
                    'imageable_id' => $gallery->id,
                    'imageable_type' => Gallery::class,
                ]);
        }
    }
}