<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserController
{
    /**
     * Display the current loggedin user or the specified user.
     *
     * @param  User|null  $user
     *
     * @return UserResource
     */
    public function show(?User $user = null) : UserResource
    {
        return new UserResource($user ?? auth()->user());
    }
}
