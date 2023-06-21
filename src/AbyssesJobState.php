<?php

namespace Biigle\Modules\abysses;

use Biigle\Modules\abysses\DataBase\Factories\AbyssesJobStateFactory;
use Biigle\Traits\HasConstantInstances;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AbyssesJobState extends Model
{
    use HasConstantInstances, HasFactory;

    /**
     * The constant instances of this model.
     *
     * @var array
     */
    const INSTANCES = [
        // The novelty retrain recognition stage.
        'labelRecognition' => 'label-recognition',
        // A failure during novelty retrain recognition .
        'failedLabelRecognition' => 'failed-label-recognition',
        // The novelty test recognition stage.
        'retrainingProposals' => 'retraining-proposals',
        // A failure during novelty test recognition .
        'failedRetrainingProposals' => 'failed-retraining-proposals',
    ];

    /**
     * Don't maintain timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return AbyssesJobStateFactory::new();
    }
}
