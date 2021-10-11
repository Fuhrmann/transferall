<?php

namespace App\Hateoas;

use App\Models\Transaction;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class TransactionHateoas
{
    use CreatesLinks;

    /**
     * Get the HATEOAS link to view the transaction.
     *
     * @param  Transaction  $transaction
     *
     * @return Link
     */
    public function self(Transaction $transaction) : Link
    {
        return $this->link('api.transactions.show', ['transaction' => $transaction]);
    }

    /**
     * Get the HATEOAS link to create a new transaction.
     *
     * @return Link
     */
    public function create() : Link
    {
        return $this->link('api.transactions.store');
    }

}
