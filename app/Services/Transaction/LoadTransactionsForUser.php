<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LoadTransactionsForUser
{
    public function load(User $user) : Collection
    {
        return Transaction::with('payeeWallet.owner')
            ->where(function (Builder $q) use ($user) {
                $q->where('wallet_payer_id', $user->walletId())
                    ->orWhere('wallet_payee_id', $user->walletId());
            })
            ->orderBy('created_at')
            ->get()
            ->map(function (Transaction $transaction) use ($user) {
                return [
                    'id'      => $transaction->id,
                    'from'    => $transaction->payer()->name,
                    'to'      => $transaction->payee()->name,
                    'ammount' => $transaction->ammount,
                    'in'      => $transaction->payee()->id === $user->id,
                    'date'    => $transaction->created_at,
                ];
            });
    }
}
