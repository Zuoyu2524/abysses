<?php

namespace Biigle\Modules\abysses\DataBase\Factories;

use Biigle\Modules\abysses\AbyssesJobState;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbyssesJobStateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AbyssesJobState::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->username(),
        ];
    }
}
