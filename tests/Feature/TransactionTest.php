<?php

namespace Tests\Feature;

use App\Notifications\MoneyReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\CreatesTransaction;
use Tests\Traits\LoggedIn;

class TransactionTest extends TestCase
{
    use RefreshDatabase, LoggedIn, CreatesTransaction;

    /**
     * @var string|null
     */
    private ?string $prefix = null;

    public function test_transfer_money_page_rendered() : void
    {
        $response = $this->get(route('transaction.create'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_can_transfer_successfully() : void
    {
        $this
            ->followingRedirects()
            ->postNewTransaction('transaction.store')
            ->assertStatus(Response::HTTP_OK)
            ->assertSeeText('Transferência realizada com sucesso');
    }

    public function test_session_receive_errors_if_something_is_wrong() : void
    {
        $this
            ->postNewTransaction('transaction.store', true)
            ->assertSessionHas('errors');
    }

    public function test_payee_is_notified_after_transaction() : void
    {
        $this
            ->followingRedirects()
            ->postNewTransaction('transaction.store')
            ->assertStatus(Response::HTTP_OK)
            ->assertSeeText('Transferência realizada com sucesso');

        Notification::assertSentTo($this->payee->profile, MoneyReceived::class);
    }

}
