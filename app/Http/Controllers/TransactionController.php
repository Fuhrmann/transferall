<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionValidationException;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Notifications\NewTransfer;
use App\Services\Transaction\TransactionHandler;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Throwable;

class TransactionController extends Controller
{

    public function __construct(private TransactionHandler $transactionHandler, private DatabaseManager $db)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create() : View
    {
        $wallets = Wallet::with('owner')->where('owner_id', '<>', auth()->user()->id)->get();

        return view('transaction.create', compact('wallets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TransactionRequest  $request
     *
     * @throws Throwable
     * @return RedirectResponse
     */
    public function store(TransactionRequest $request) : RedirectResponse
    {
        try {
            $transaction = $this->db->transaction(function () use ($request) {
                return $this->transactionHandler->create(
                    $request->get('wallet_payer_id', auth()->user()->walletId()),
                    $request->get('wallet_payee_id'),
                    $request->get('ammount', 0)
                );
            });

            // Eu deixe essa chamada para notificação tanto aqui como no controller da API
            // só para deixar explicito quando e como a notificação é enviada. Eu entendo
            // que o código está se repetindo em dois controles, mas neste caso eu acho
            // que deixar explícito é melhor do que esconder essas chamadas
            $transaction->payee()->notify(new NewTransfer($transaction));

            session()->flash('message', 'Transferência realizada com sucesso!');
            return redirect()->route('dashboard');
        } catch (TransactionValidationException $e) {
            return back()->withErrors(new MessageBag(['catch_exception' => $e->getMessage()]));
        } catch (Exception $e) {
            Log::error($e);
            return back()->withErrors(new MessageBag(['catch_exception' => 'Houve um erro ao realizar a transferência, contate o suporte técnico.']));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction  $transaction
     *
     * @return View
     */
    public function show(Transaction $transaction) : View
    {
        return view('transaction.show', compact('transaction'));
    }

}
