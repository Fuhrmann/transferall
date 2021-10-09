<?php

namespace Database\Factories;

use App\Models\IndividualUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndividualUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IndividualUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'user_id'          => User::factory()->create(),
            'cpf'              => $this->faker->unique()->cpf,
            'date_of_birthday' => $this->faker->date(),
        ];
    }

    /**
     * @param  array  $userData
     *
     * @return IndividualUserFactory
     */
    public function withCustomUserData(array $userData) : self
    {
        return $this->state(function () use ($userData) {
            return [
                'user_id' => User::factory($userData)->create(),
            ];
        });
    }
}
