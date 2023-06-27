<?php

namespace Biigle\Modules\Abysses\DataBase\Factories;

use Biigle\Modules\Abysses\AbyssesTrain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Biigle\Image;
use Biigle\Modules\Abysses\AbyssesJob;


class AbyssesTrainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AbyssesTrain::class;

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
        ];
    }
}
