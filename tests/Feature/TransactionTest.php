<?php

namespace Tests\Feature;

use App\Contracts\TransactionAuthorizerContract;
use App\Models\CompanyUser;
use App\Models\IndividualUser;
use App\Notifications\NewTransfer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\LoggedIn;

class TransactionTest extends TestCase
{
    use RefreshDatabase, LoggedIn;

    private Model $payer;

    private Model $payee;

    public function test_transfer_money_page_rendered() : void
    {
        $response = $this->get(route('transaction.create'));
        $response->assertStatus(200);
    }

    public function test_user_can_transfer_successfully() : void
    {
        $this
            ->followingRedirects()
            ->postNewTransaction()
            ->assertStatus(200)
            ->assertSeeText('Transferência realizada com sucesso');
    }

    public function test_session_receive_errors_if_something_is_wrong() : void
    {
        $this
            ->postNewTransaction(true)
            ->assertSessionHas('errors');
    }

    public function test_payee_is_notified_after_transaction() : void
    {
        $this
            ->followingRedirects()
            ->postNewTransaction()
            ->assertStatus(200)
            ->assertSeeText('Transferência realizada com sucesso');

        Notification::assertSentTo($this->payee->profile, NewTransfer::class);
    }

    /**
     * Send a new transaction using fake data.
     *
     * @param  bool  $shouldFail
     *
     * @return TestResponse
     */
    protected function postNewTransaction(bool $shouldFail = false) : TestResponse
    {
        Notification::fake();

        $this->payer = IndividualUser::factory()->create();
        $this->payee = CompanyUser::factory()->create();
        $this->login($this->payer->profile);

        return $this->post(route('transaction.store'), [
            'wallet_payer_id' => $this->payer->profile->walletId(),
            'wallet_payee_id' => $this->payee->profile->walletId(),
            'ammount'         => $shouldFail ? 0 : 1000,
        ]);
    }

    protected function setUp() : void
    {
        parent::setUp();

        $this->app->bind(TransactionAuthorizerContract::class, function () {
            return new class implements TransactionAuthorizerContract {
                public function isAuthorized() : bool
                {
                    return true;
                }
            };
        });

        $this->login();
    }
}
