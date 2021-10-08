<?php

namespace App\Services;

use App\Models\Wallet;

class BalanceChecker
{

    /**
     * Checks if a wallet has balance.
     *
     * @param  Wallet  $wallet
     *
     * @return bool
     */
    public function hasBalance(Wallet $wallet) : bool
    {
        return (float) $wallet->ammount > 0;
    }

}
