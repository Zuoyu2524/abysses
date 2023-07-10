<?php

namespace Biigle\Modules\abysses\Notifications;

class LabelRecognitionFailed extends JobStateChanged
{
    /**
     * Get the title for the state change.
     *
     * @param AbyssesJob $job
     * @return string
     */
    protected function getTitle($job)
    {
        return "Abysses job {$job->id} failed";
    }

    /**
     * Get the message for the state change.
     *
     * @param AbyssesJob $job
     * @return string
     */
    protected function getMessage($job)
    {
        return "Abysses job {$job->id} failed during Label Recognition. Please notify the BIIGLE administrators.";
    }
}
