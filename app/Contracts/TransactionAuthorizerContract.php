<?php

namespace App\Contracts;

interface TransactionAuthorizerContract
{
    /**
     * Indicate if the transaction is authorized.
     *
     * @return bool
     */
    public function isAuthorized() : bool;

}
