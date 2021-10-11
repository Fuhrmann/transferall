<?php

namespace App\Hateoas;

use App\Models\User;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class UserHateoas
{
    use CreatesLinks;

    /**
     * Get the HATEOAS link to view the user.
     *
     * @param  User  $user
     *
     * @return Link
     */
    public function self(User $user) : Link
    {
        return $this->link('api.users.show', ['user' => $user]);
    }
}
