<?php

namespace App\Services\Transaction\Validator;

use App\Exceptions\TransactionValidationException;
use Closure;

class ValidateAmmount implements TransactionValidation
{
    /**
     * @throws TransactionValidationException
     */
    public function validate(array $data, Closure $next) : bool
    {
        if ($data['ammount'] <= 0) {
            throw TransactionValidationException::withMessages(['ammount' => 'Por favor informe um valor para transferir.']);
        }

        return $next($data);
    }
}
