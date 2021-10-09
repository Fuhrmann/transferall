<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Transaction\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'wallet_payer_id' => Wallet::factory(),
            'wallet_payee_id' => Wallet::factory(),
            'ammount'         => $this->faker->numberBetween(2, 10000),
            'status'          => TransactionStatus::APPROVED,
        ];
    }

    /**
     * Generate test transaction from and to the same user.
     *
     * @param  int  $id
     *
     * @return Factory
     */
    public function fromSameUser(int $id) : Factory
    {
        $user = User::factory(['id' => $id])->create();

        return $this->state(function () use ($user) {
            return [
                'wallet_payer_id' => $user->walletId(),
                'wallet_payee_id' => $user->walletId(),
            ];
        });
    }
}
