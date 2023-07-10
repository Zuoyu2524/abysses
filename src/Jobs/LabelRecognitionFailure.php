<?php

namespace Biigle\Modules\abysses\Jobs;

use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Modules\abysses\Notifications\LabelRecognitionFailed;

class LabelRecognitionFailure extends JobFailure
{
    /**
     * {@inheritdoc}
     */
    protected function updateJobState(AbyssesJob $job)
    {
        $job->state_id = State::failedLabelRecognitionId();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendNotification(AbyssesJob $job)
    {
        $job->user->notify(new LabelRecognitionFailed($job));
    }
}
