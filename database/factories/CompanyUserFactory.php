<?php

namespace Database\Factories;

use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'user_id'      => User::factory()->create(),
            'cnpj'         => $this->faker->unique()->cnpj,
            'trading_name' => $this->faker->company(),
        ];
    }
}
