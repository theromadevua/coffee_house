<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path' => 'images/' . $this->faker->uuid . '.jpg',
            'caption' => $this->faker->sentence(),
            'imageable_id' => null, 
            'imageable_type' => null, 
        ];
    }

    public function forGallery(): static
    {
        return $this->state(fn (array $attributes) => [
            'imageable_id' => Gallery::factory(),
            'imageable_type' => Gallery::class,
        ]);
    }

    public function forUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'imageable_id' => User::factory(),
            'imageable_type' => User::class,
        ]);
    }
}