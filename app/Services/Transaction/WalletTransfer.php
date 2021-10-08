<?php

namespace App\Services\Transaction;

use App\Models\Transaction;

class WalletTransfer
{
    /**
     * Transfer money from the wallets specified in the transaction.
     *
     * @param  Transaction  $transaction  The transaction details.
     */
    public function transferWithTransaction(Transaction $transaction) : void
    {
        // Nenhuma validação ocorre aqui pois a transferência entre
        // carteiras ocorre somente em uma transação e a validação
        // já está acontecendo na hora de criar a mesma
        $transaction->load('payerWallet', 'payeeWallet');
        $transaction->payerWallet->subtract($transaction->ammount);
        $transaction->payeeWallet->add($transaction->ammount);
    }
}
