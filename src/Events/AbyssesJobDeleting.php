<?php

namespace Biigle\Modules\abysses\Events;

use Biigle\Modules\abysses\AbyssesJob;

class AbyssesJobDeleting
{
    /**
     * The job that caused this event.
     *
     * @var MaiaJob
     */
    public $job;

    /**
     * Create a new instance
     *
     * @param MaiaJob $job
     */
    public function __construct(AbyssesJob $job)
    {
        $this->job = $job;
    }
}
