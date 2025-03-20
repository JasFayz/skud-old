<?php

namespace Modules\Skud\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\Entities\Floor;

class FloorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Floor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Floor',
            'label' => '01',
        ];
    }
}

