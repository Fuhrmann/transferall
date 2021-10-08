<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\TransactionValidationException;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Notifications\NewTransfer;
use App\Services\Transaction\TransactionHandler;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionController
{

    public function __construct(private TransactionHandler $transactionHandler, private DatabaseManager $db)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TransactionRequest  $request
     *
     * @throws Throwable
     * @return JsonResponse|TransactionResource
     */
    public function store(TransactionRequest $request) : JsonResponse|TransactionResource
    {
        try {
            // Dois controllers parecidos, que fazem coisas iguais. Porém, mantendo-os
            // separados podemos permitir que seja feita a manutenção de forma mais
            // rápida, pois este é da API e pode se comportar de maneira diferente
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

            return new TransactionResource($transaction);
        } catch (TransactionValidationException $e) {
            return response()->json([
                'code'    => 401,
                'message' => $e->getMessage(),
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'code'    => 500,
                'message' => 'Houve um erro ao realizar a transferência, contate o suporte técnico.',
            ]);
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
