<?php

namespace App\Services\Transaction\Validator;

use Closure;

interface TransactionValidation
{
    public function validate(array $data, Closure $next) : bool;
}
