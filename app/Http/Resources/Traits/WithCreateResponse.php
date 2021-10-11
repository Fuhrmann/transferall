<?php

namespace App\Http\Resources\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait WithCreateResponse
{
    /**
     * Customize the response for a request.
     *
     * @param  Request  $request
     * @param  JsonResponse  $response
     *
     * @return void
     */
    public function withResponse($request, $response) : void
    {
        $response->setStatusCode(Response::HTTP_CREATED);
    }
}
