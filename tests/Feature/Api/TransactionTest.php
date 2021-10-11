<?php

namespace Tests\Feature\Api;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\CreatesTransaction;
use Tests\Traits\LoggedIn;

class TransactionTest extends TestCase
{
    use RefreshDatabase, LoggedIn, CreatesTransaction;

    public function test_create_new_transaction() : void
    {
        $response = $this->postNewTransaction('api.transactions.store');
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_create_with_validation_error() : void
    {
        $response = $this->postNewTransaction('api.transactions.store', true);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_show_transaction() : void
    {
        $transaction = Transaction::factory(['id' => 1, 'ammount' => 9000])->create();

        $response = $this->get(route('api.transactions.show', $transaction));
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['id' => 1, 'ammount' => 9000]);
    }

}
