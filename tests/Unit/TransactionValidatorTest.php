<?php

namespace Tests\Unit;

use App\Contracts\TransactionAuthorizerContract;
use App\Exceptions\TransactionValidationException;
use App\Models\CompanyUser;
use App\Models\IndividualUser;
use App\Models\Wallet;
use App\Services\Transaction\Validator\TransactionValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_is_invalid_if_no_ammount() : void
    {
        $this
            ->withoutExceptionHandling()
            ->expectException(TransactionValidationException::class);

        $payerWallet = Wallet::factory()->create();
        $payeeWallet = Wallet::factory()->create();

        $validator = app(TransactionValidator::class);
        $validator->validate($payerWallet->id, $payeeWallet->id, 0);
    }

    public function test_transaction_is_valid_if_it_has_ammount() : void
    {
        $payerWallet = Wallet::factory()->create();
        $payeeWallet = Wallet::factory()->create();

        $validator = app(TransactionValidator::class);
        $this->assertTrue($validator->validate($payerWallet->id, $payeeWallet->id, 1000));
    }

    public function test_transaction_is_invalid_if_payer_is_company() : void
    {
        $this
            ->withoutExceptionHandling()
            ->expectException(TransactionValidationException::class);

        $payer = CompanyUser::factory()->create();
        $payee = IndividualUser::factory()->create();

        $validator = app(TransactionValidator::class);
        $validator->validate($payer->profile->walletId(), $payee->profile->walletId(), 1000);
    }

    public function test_transaction_is_valid_if_payer_is_individual() : void
    {
        $payer = IndividualUser::factory()->create();
        $payee = CompanyUser::factory()->create();

        $validator = app(TransactionValidator::class);
        $this->assertTrue($validator->validate($payer->profile->walletId(), $payee->profile->walletId(), 1000));
    }

    public function test_transaction_is_invalid_if_payer_has_no_balance() : void
    {
        $this
            ->withoutExceptionHandling()
            ->expectException(TransactionValidationException::class);

        $payerWallet = Wallet::factory(['ammount' => 0])->create();
        $payeeWallet = Wallet::factory()->create();

        $validator = app(TransactionValidator::class);
        $validator->validate($payerWallet->id, $payeeWallet->id, 0);
    }

    public function test_transaction_is_invalid_if_gateway_dont_authorize() : void
    {
        $this
            ->withoutExceptionHandling()
            ->expectException(TransactionValidationException::class);

        $this->app->bind(TransactionAuthorizerContract::class, function () {
            return new class implements TransactionAuthorizerContract {
                public function isAuthorized() : bool
                {
                    return false;
                }
            };
        });

        $payerWallet = Wallet::factory()->create();
        $payeeWallet = Wallet::factory()->create();

        $validator = app(TransactionValidator::class);
        $validator->validate($payerWallet->id, $payeeWallet->id, 0);
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
    }


}
