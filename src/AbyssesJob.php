<?php

namespace Biigle\Modules\abysses;

use Biigle\Modules\abysses\DataBase\Factories\AbyssesJobFactory;
use Biigle\Modules\abysses\Events\AbyssesJobCreated;
use Biigle\Modules\abysses\Events\AbyssesJobDeleting;
use Biigle\Traits\HasJsonAttributes;
use Biigle\User;
use Biigle\Volume;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbyssesJob extends Model
{
    use HasJsonAttributes, HasFactory;

    /**
     * The attributes that should be casted to native types.
     B
     * @var array
     */
    protected $casts = [
        'attrs' => 'array',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => AbyssesJobCreated::class,
        'deleting' => AbyssesJobDeleting::class,
    ];

    /**
     * The volume, this Abysses job belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function volume()
    {
        return $this->belongsTo(Volume::class);
    }

    /**
     * The user who created this Abysses job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The state of this Abysses job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(AbyssesJobState::class);
    }

    /**
     * The retrain tasks of this Abysses job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function abyssestrain()
    {
        return $this->hasMany(AbyssesTrain::class, 'job_id');
    }
    
    /**
     * The test tasks of this Abysses job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function abyssestest()
    {
        return $this->hasMany(AbyssesTest::class, 'job_id');
    }

    /**
     * Determine if the job is currently running train or test.
     *
     * @return boolean
     */
    public function isRunning()
    {
        return $this->state_id === AbyssesJobState::labelRecognitionId()
            || $this->state_id === AbyssesJobState::retrainingProposalsId();
    }

    /**
     * Determine if the job failed during novelty detection or object detection.
     *
     * @return boolean
     */
    public function hasFailed()
    {
        return $this->state_id === AbyssesJobState::failedLabelRecognitionId()
            || $this->state_id === AbyssesJobState::failedRetrainingProposalsId();
    }

    /**
     * Determine if the job requires a user action to continue
     *
     * @return boolean
     */
    public function requiresAction()
    {
        return $this->state_id === AbyssesJobState::retrainingProposalsId();
    }
    
    /**
     * Get the configured parameters of this job.
     *
     * @return array
     */
    public function getParamsAttribute()
    {
        return $this->getJsonAttr('params', []);
    }

    /**
     * Set the configured parameters of this job.
     *
     * @param array $params
     */
    public function setParamsAttribute(array $params)
    {
        return $this->setJsonAttr('params', $params);
    }

    /**
     * Get the error information on this job (if any).
     *
     * @return array
     */
    public function getErrorAttribute()
    {
        return $this->getJsonAttr('error', []);
    }

    /**
     * Set the error information for this job.
     *
     * @param array $error
     */
    public function setErrorAttribute(array $error)
    {
        return $this->setJsonAttr('error', $error);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return AbyssesJobFactory::new();
    }
}
