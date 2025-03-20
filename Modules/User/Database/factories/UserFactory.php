<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Skud\Entities\Company;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => \Hash::make('password')
        ];
    }
}

