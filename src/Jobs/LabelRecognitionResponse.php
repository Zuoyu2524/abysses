<?php

namespace Biigle\Modules\abysses\Jobs;

use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Modules\abysses\Notifications\LabelRecognitionComplete;
use Biigle\Modules\abysses\AbyssesTest;
use Exception;
use Queue;

class LabelRecognitionResponse extends JobResponse
{
    /**
     * {@inheritdoc}
     */
    protected function getExpectedJobStateId()
    {
        return State::labelRecognitionId();
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Queue::push(new LabelRecognitionFailure($this->jobId, $exception));
    }

    /**
     * {@inheritdoc}
     */
    protected function insertLabelChunk(array $chunk)
    {
        AbyssesTest::insert($chunk);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreatedLabels(AbyssesJob $job)
    {
        return $job->abyssestest();
    }

    /**
     * {@inheritdoc}
     */
    protected function updateJobState(AbyssesJob $job)
    {
        $job->state_id = State::successId();
        $job->save();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendNotification(AbyssesJob $job)
    {
        $job->user->notify(new LabelRecognitionComplete($job));
    }

    /**
     * {@inheritdoc}
     */
    protected function getPatchStorageDisk()
    {
        return config('abysses.training_proposal_storage_disk');
    }
}
