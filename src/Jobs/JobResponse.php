<?php

namespace Biigle\Modules\abysses\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\abysses\AbyssesTest;
use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Shape;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * This job is executed on the machine running BIIGLE to store the results of label recognition
 */
class JobResponse extends Job implements ShouldQueue
{
    /**
     * ID of the Abysses job.
     *
     * @var int
     */
    public $jobId;

    /**
     * Image ID, center points, radii and scores of annotations to create.
     * Example:
     * [
     *     [image_id, center_x, center_y, radius, score],
     *     [image_id, center_x, center_y, radius, score],
     *     ...
     * ]
     *
     * @var array
     */
    public $labels;

    /**
     * Create a new instance
     *
     * @param int $jobId
     * @param array $labels
     */
    public function __construct($jobId, $labels)
    {
        $this->jobId = $jobId;
        $this->labels = $labels;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        $job = AbyssesJob::where('state_id', $this->getExpectedJobStateId())
            ->find($this->jobId);
        if ($job === null) {
            // Ignore the results if the job no longer exists for some reason.
            return;
        }

        // Make sure to roll back any DB modifications if an error occurs.
        DB::transaction(function () use ($job) {
            $this->createAbyssesTests();
            $this->updateJobState($job);
        });

        $this->sendNotification($job);
    }

    /**
     * Create abysses annotations from the training proposals.
     */
    protected function createAbyssesTests()
    {
        $labels = array_map(function ($label) {
            return $this->createAbyssesTest($label);
        }, $this->labels);

        // Chunk the insert because PDO's maximum number of query parameters is
        // 65535. Each annotation has 7 parameters so we can store roughly 9000
        // annotations in one call.
        $labels = array_chunk($labels, 9000);
        array_walk($labels, function ($chunk) {
            $this->insertLabelChunk($chunk);
        });
    }

    /**
     * Get the job state ID that the job is required to have to be modified.
     *
     * @return int
     */
    protected function getExpectedJobStateId()
    {
        return null;
    }

    /**
     * Create an insert array for a MAIA annotation.
     *
     * @param array $label
     *
     * @return array
     */
    protected function createAbyssesTest($label)
    {
        return [
            'job_id' => $this->jobId,
            'image_id' => $label[0],
            'label' => $label[1],
            'is_train' => false,
        ];
    }

    /**
     * Insert one chunk of the MAIA annotations that should be created into the database.
     *
     * @param array $chunk
     */
    protected function insertLabelChunk(array $chunk)
    {
        //
    }

    /**
     * Get a query for the annotations that have been created by this job.
     *
     * @param AbyssesJob $job
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function getCreatedLabels(AbyssesJob $job)
    {
        return $job->labels();
    }

    /**
     * Update the state of the Abysses job after processing the response.
     *
     * @param AbyssesJob $job
     */
    protected function updateJobState(AbyssesJob $job)
    {
        //
    }

    /**
     * Send the notification about the completion to the creator of the job.
     *
     * @param AbyssesJob $job
     */
    protected function sendNotification(AbyssesJob $job)
    {
        //
    }

    /**
     * Get the storage disk to store the annotation patches to.
     */
    protected function getPatchStorageDisk()
    {
        //
    }
}
