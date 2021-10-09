<?php

namespace Tests\Unit;

use App\Services\Transaction\TransactionStatus;
use Tests\TestCase;

class TransactionStatusTest extends TestCase
{
    public function test_get_all_transaction_status() : void
    {
        $this->assertIsArray(TransactionStatus::all());
    }

    public function test_if_transaction_status_not_found_is_unknow() : void
    {
        $this->assertSame('Unknow', TransactionStatus::getStatus(9999));
    }
}
