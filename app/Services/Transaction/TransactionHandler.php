<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Services\Transaction\Validator\TransactionValidator;
use App\TransactionStatus;

class TransactionHandler
{

    public function __construct(private TransactionValidator $validator, private WalletTransfer $walletTransfer)
    {
    }


    /**
     * Creates a new transaction.
     *
     * @param  int  $walletPayerId  The wallet's ID where the money comes from.
     * @param  int  $walletPayeeId  The wallet's ID where the money is being sent to.
     * @param  float  $ammount  The ammount being sent.
     *
     * @return Transaction
     */
    public function create(int $walletPayerId, int $walletPayeeId, float $ammount) : Transaction
    {
        // Tentei manter o código simples e que seja fácil de compreender quando batemos o olho
        // As rotinas acontecem na ordem que é para acontecer e pedaços de código que realizam
        // tarefas diferentes, ficam em classes diferentes para melhor organização
        $this->validator->validate($walletPayerId, $walletPayeeId, $ammount);

        $transaction = Transaction::create([
            'wallet_payer_id' => $walletPayerId,
            'wallet_payee_id' => $walletPayeeId,
            'ammount'         => $ammount,
            'status'          => TransactionStatus::APPROVED,
        ]);

        $this->walletTransfer->transferWithTransaction($transaction);

        return $transaction;
    }
}
