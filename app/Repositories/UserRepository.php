<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}