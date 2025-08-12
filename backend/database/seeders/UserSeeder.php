<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => Role::ADMIN
        ]);

        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => Role::USER
        ]);

        User::factory(10)->create();

        $admin->gallery()->create([
            'name' => 'Admin\'s Personal Gallery',
        ]);

        $testUser->gallery()->create([
            'name' => 'Test User\'s Gallery'
        ]);
    }
}