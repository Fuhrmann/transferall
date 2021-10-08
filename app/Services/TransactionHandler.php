<?php

namespace App\Services;

class TransactionHandler
{
    public function create(int $payeeId, float $ammount)
    {
        // CASO SEJA LOJISTA< NAO PODE ENVIAR DINHEIRO!
        // VALIDAR SE TEM SALDO!
        // CONSULTAR SERVICO EXTERNO (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6)
    }
}
