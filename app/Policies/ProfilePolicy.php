<?php

namespace App\Policies;

use App\Models\User;

class ProfilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function show(User $user, User $model)
    {

        return $user->id === $model->id;
    }

    public function update(User $user, User $model)
    {

        return $user->id === $model->id;
    }
}
