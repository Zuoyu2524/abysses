<?php

namespace Biigle\Modules\abysses\DataBase\Factories;

use Biigle\Modules\abysses\abyssesTest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Biigle\Image;
use Biigle\Modules\abysses\AbyssesJob;


class AbyssesTestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AbyssesTest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image_id' => Image::factory(),
            'job_id'   => MaiaJob::factory(),
            'label'    => $this->faker->username(),
            'is_train' => false,
        ];
    }
}
