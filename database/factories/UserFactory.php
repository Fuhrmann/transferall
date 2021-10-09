<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure() : self
    {
        return $this->afterCreating(function (User $user) {
            Wallet::factory(['owner_id' => $user->id])->create();
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'name'           => $this->faker->name,
            'email'          => $this->faker->unique()->safeEmail(),
            'password'       => '$2y$10$huzA8b9M9SFA2yIt34BQA.Adr31HjS/yEM5c8m5ltHA5EE4ZdOO8S', //123456
            'remember_token' => Str::random(10),
            'active'         => 1,
        ];
    }
}
