<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{    
    public function __construct(private UserRepository $userRepository) {}

    public function deleteUser(int $user_id): void
    {
        $user = $this->userRepository->findById($user_id);
        $this->userRepository->delete($user);
    }
}