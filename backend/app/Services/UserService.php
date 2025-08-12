<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{

    // ====== Users ======

    /**
     * @return Collection
     */
    public function listUsers(): Collection
    {
        return User::all();
    }

    /**
     * @param int $id
     * @return User
     */
    public function findUser(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? Role::USER,
        ]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUser(int $id, array $data): User
    {
        $item = $this->findUser($id);
        $item->update($data);
        return $item;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $item = $this->findUser($id);
        return $item->delete();
    }
}
