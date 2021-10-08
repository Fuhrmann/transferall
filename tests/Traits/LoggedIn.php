<?php

namespace Tests\Traits;

use App\Models\IndividualUser;
use App\Models\User;

trait LoggedIn
{
    /**
     * Creates an user and logs in.
     *
     * @param  User|null  $user
     *
     * @return User
     */
    public function login(?User $user = null) : User
    {
        if (is_null($user)) {
            $user = IndividualUser::factory()->create()->profile;
        }

        $this->be($user);

        return $user;
    }
}
