<?php

namespace App\Http\Resources;

use App\Hateoas\UserHateoas;
use GDebrauwer\Hateoas\Traits\HasLinks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use HasLinks;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'balance'    => $this->whenLoaded('wallet', function () {
                return (float) $this->wallet->ammount;
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '_links'     => $this->links(UserHateoas::class),
        ];
    }
}
