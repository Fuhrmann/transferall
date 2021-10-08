<?php

namespace App\Services\Transaction\Validator;

use App\Models\Wallet;
use Illuminate\Pipeline\Pipeline;

class TransactionValidator
{

    private array $validations = [
        ValidateAmmount::class,
        IsNotCompany::class,
        HasEnoughBalance::class,
        GatewayAuthorization::class
    ];

    public function __construct(private Pipeline $pipeline)
    {
    }

    /**
     * Validates a transaction.
     *
     * @param  int  $walletPayerId The wallet's ID where the money comes from.
     * @param  int  $walletPayeeId The wallet's ID where the money is being sent to.
     * @param  float  $ammount The ammount being sent.
     *
     * @return bool
     */
    public function validate(int $walletPayerId, int $walletPayeeId, float $ammount) : bool
    {
        $payeeWallet = Wallet::with('owner')->find($walletPayeeId);
        $payerWallet = Wallet::with('owner')->find($walletPayerId);

        return $this->pipeline
            ->send(compact('payeeWallet', 'payerWallet', 'ammount'))
            ->through($this->validations)
            ->via('validate')
            ->then(function () : bool {
                return true;
            });
    }
}
