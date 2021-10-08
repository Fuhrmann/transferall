<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Transaction\LoadTransactionsForUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadTransactionsForUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_transactions_are_loaded() : void
    {
        Transaction::factory(10)->fromSameUser(99)->create();

        $service = new LoadTransactionsForUser();
        $this->assertCount(10, $service->load(User::find(99)));
    }

    public function test_if_transactions_are_loaded_even_if_empty() : void
    {
        $user = User::factory()->create();

        $service = new LoadTransactionsForUser();
        $this->assertCount(0, $service->load($user));
    }
}
