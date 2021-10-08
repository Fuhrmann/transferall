<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Services\Transaction\WalletTransfer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_payee_wallet_is_credited_with_money_from_transaction()
    {
        $transaction = Transaction::factory(['ammount' => 100])->create();
        $currentPayeeAmmount = $transaction->payeeWallet->ammount;

        $transferService = new WalletTransfer();
        $transferService->transferWithTransaction($transaction);

        $this->assertSame((float) $currentPayeeAmmount + 100, $transaction->payeeWallet->ammount);
    }

    public function test_payer_wallet_is_subtracted_from_transaction()
    {
        $transaction = Transaction::factory(['ammount' => 100])->create();
        $currentPayerAmmount = $transaction->payerWallet->ammount;

        $transferService = new WalletTransfer();
        $transferService->transferWithTransaction($transaction);

        $this->assertSame((float) $currentPayerAmmount - 100, $transaction->payerWallet->ammount);
    }
}
