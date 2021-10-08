<?php

namespace App\Services\Transaction\Validator;

use App\Exceptions\TransactionValidationException;
use App\Services\BalanceChecker;
use Closure;

class HasEnoughBalance implements TransactionValidation
{
    public function __construct(private BalanceChecker $balanceChecker)
    {
    }

    /**
     * @throws TransactionValidationException
     */
    public function validate(array $data, Closure $next) : bool
    {
        if (! $this->balanceChecker->hasBalance($data['payerWallet'])) {
            throw TransactionValidationException::withMessages(['user' => 'You dont have enough balance to transfer from your account.']);
        }

        return $next($data);
    }
}
