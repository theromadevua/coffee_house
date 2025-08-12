<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Main Dishes',
            'description' => 'Delicious main courses for any meal.',
        ]);

        Category::create([
            'name' => 'Desserts',
            'description' => 'Sweet treats to end your meal.',
        ]);

        Category::factory(3)->create();
    }
}