<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Transaction\LoadTransactionsForUser;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private LoadTransactionsForUser $loadTransactionsForUser)
    {
    }

    public function index() : View
    {
        $user = auth()->user();

        $transactions = $this
            ->loadTransactionsForUser
            ->load($user)
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

        return view('dashboard', compact('transactions'));
    }
}
