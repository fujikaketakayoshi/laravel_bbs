<?php

namespace App\Services;

use App\Models\User;

class UserService
{    
    public function deleteUser(int $user_id): void
    {
        $user = User::findOrFail($user_id);
        $user->delete();
    }
}