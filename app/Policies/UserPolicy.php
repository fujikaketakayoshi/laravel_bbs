<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $target
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $target)
    {
        return $user->id === $target->id;
    }
}
