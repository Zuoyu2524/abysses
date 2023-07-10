<?php

namespace Biigle\Modules\abysses\DataBase\Factories;

use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState;
use Biigle\User;
use Biigle\Volume;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbyssesJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AbyssesJob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'volume_id' => Volume::factory(),
            'user_id' => User::factory(),
            'state_id' => function () {
                return AbyssesJobState::noveltyDetectionId();
            },
        ];
    }
}
