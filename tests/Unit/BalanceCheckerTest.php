<?php

namespace Tests\Unit;

use App\Models\Wallet;
use App\Services\BalanceChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceCheckerTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_check_balance_with_ammount() : void
    {
        $wallet = Wallet::factory(['ammount' => 1])->create();

        $balanceChecker = new BalanceChecker();
        $this->assertTrue($balanceChecker->hasBalance($wallet));
    }

    public function test_if_false_if_it_has_no_balance() : void
    {
        $wallet = Wallet::factory(['ammount' => 0])->create();

        $balanceChecker = new BalanceChecker();
        $this->assertFalse($balanceChecker->hasBalance($wallet));
    }
}
