<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function view(User $user, User $model): bool
    {
        return $user->role_id === 1 || $user->id === $model->id;
    }
    
    public function modify(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
    
    public function delete(User $user, User $model): bool
    {
        return $user->role_id === 1 || $user->id === $model->id;
    }
}
