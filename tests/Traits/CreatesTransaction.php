<?php

namespace Tests\Traits;

use App\Contracts\TransactionAuthorizerContract;
use App\Models\CompanyUser;
use App\Models\IndividualUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;

trait CreatesTransaction
{
    /**
     * @var Model
     */
    private Model $payee;

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

    /**
     * Create a new transaction using fake data.
     *
     * @param  string  $route
     * @param  bool  $shouldFail
     *
     * @return TestResponse
     */
    protected function postNewTransaction(string $route, bool $shouldFail = false) : TestResponse
    {
        Notification::fake();

        $this->payer = IndividualUser::factory()->create();
        $this->payee = CompanyUser::factory()->create();
        $this->login($this->payer->profile);

        return $this->post(route($route), [
            'wallet_payer_id' => $this->payer->profile->walletId(),
            'wallet_payee_id' => $this->payee->profile->walletId(),
            'ammount'         => $shouldFail ? 0 : 1000,
        ]);
    }
}
