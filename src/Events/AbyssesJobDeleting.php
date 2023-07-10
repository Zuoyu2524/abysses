<?php

namespace Biigle\Modules\abysses\Events;

use Biigle\Modules\abysses\AbyssesJob;

class AbyssesJobDeleting
{
    /**
     * The job that caused this event.
     *
     * @var AbyssesJob
     */
    public $job;

    /**
     * Create a new instance
     *
     * @param AbyssesJob $job
     */
    public function __construct(AbyssesJob $job)
    {
        $this->job = $job;
    }
}
