<?php

namespace Biigle\Modules\abysses\Notifications;

class LabelRecognitionComplete extends JobStateChanged
{
    /**
     * Get the title for the state change.
     *
     * @param AbyssesJob $job
     * @return string
     */
    protected function getTitle($job)
    {
        return "label recognition job {$job->id} is finished";
    }

    /**
     * Get the message for the state change.
     *
     * @param AbyssesJob $job
     * @return string
     */
    protected function getMessage($job)
    {
        return "The results for label recognition task ready for Abysses job {$job->id}";
    }
}
