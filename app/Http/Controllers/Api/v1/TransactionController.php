<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\TransactionValidationException;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\CreateTransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\Transaction\LoadTransactionsForUser;
use App\Services\Transaction\TransactionHandler;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TransactionController
{
    public function __construct(
        private TransactionHandler $transactionHandler,
        private DatabaseManager $db,
        private LoadTransactionsForUser $loadTransactionsForUser
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return TransactionCollection
     */
    public function index() : TransactionCollection
    {
        $transactions = $this->loadTransactionsForUser->load(auth()->user());

        return new TransactionCollection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TransactionRequest  $request
     *
     * @throws Throwable
     * @return JsonResponse|TransactionResource
     */
    public function store(TransactionRequest $request) : JsonResponse|CreateTransactionResource
    {
        try {
            $transaction = $this->db->transaction(function () use ($request) {
                return $this->transactionHandler->create(
                    $request->get('wallet_payer_id', auth()->user()->walletId()),
                    $request->get('wallet_payee_id'),
                    $request->get('ammount', 0)
                );
            });

            return new CreateTransactionResource($transaction);
        } catch (TransactionValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Houve um erro ao realizar a transferência, contate o suporte técnico.',
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction  $transaction
     *
     * @return TransactionResource
     */
    public function show(Transaction $transaction) : TransactionResource
    {
        $transaction->load('payeeWallet', 'payerWallet');

        return new TransactionResource($transaction);
    }
}
