<?php

namespace App\Services\Transaction\Validator;

use App\Contracts\TransactionAuthorizerContract;
use App\Exceptions\TransactionValidationException;
use Closure;

class GatewayAuthorization implements TransactionValidation
{
    public function __construct(private TransactionAuthorizerContract $authorizer)
    {
    }

    /**
     * @throws TransactionValidationException
     */
    public function validate(array $data, Closure $next) : bool
    {
        if (! $this->authorizer->isAuthorized()) {
            throw TransactionValidationException::withMessages(['user' => 'Não autorizado. Tente novament em alguns minutos.']);
        }

        return $next($data);
    }
}
