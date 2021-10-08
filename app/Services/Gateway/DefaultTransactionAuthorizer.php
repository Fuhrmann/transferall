<?php

namespace App\Services\Gateway;

use App\Contracts\TransactionAuthorizerContract;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class DefaultTransactionAuthorizer implements TransactionAuthorizerContract
{

    private const BASE_URL = 'https://run.mocky.io/v3';

    /**
     * Indicate if the transaction is authorized.
     *
     * @return bool
     */
    public function isAuthorized() : bool
    {
        $response = Http::get(sprintf("%s/%s", self::BASE_URL, "8fafdd68-a090-496f-8c9a-3442cf30dae6"));

        return ($response->status() === Response::HTTP_OK);
    }
}
