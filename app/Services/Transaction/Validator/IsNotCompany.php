<?php

namespace App\Services\Transaction\Validator;

use App\Exceptions\TransactionValidationException;
use Closure;

class IsNotCompany implements TransactionValidation
{
    /**
     * @throws TransactionValidationException
     */
    public function validate(array $data, Closure $next) : bool
    {
        if ($data['payerWallet']->owner->companyProfile) {
            throw TransactionValidationException::withMessages(['user' => 'You are not allowed to transfer money to other accounts.']);
        }

        return $next($data);
    }
}
