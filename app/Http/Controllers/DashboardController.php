<?php

namespace App\Http\Controllers;

use App\Services\Transaction\LoadTransactionsForUser;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private LoadTransactionsForUser $loadTransactionsForUser)
    {
    }

    public function index() : View
    {
        $transactions = $this->loadTransactionsForUser->load(auth()->user());

        return view('dashboard', compact('transactions'));
    }
}
