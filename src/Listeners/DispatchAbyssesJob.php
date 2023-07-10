<?php

namespace Biigle\Modules\abysses\Listeners;

use Biigle\Modules\abysses\Events\AbyssesJobContinued;
use Biigle\Modules\abysses\Events\AbyssesJobCreated;
use Biigle\Modules\abysses\Jobs\LabelRecognitionFailure;
use Biigle\Modules\abysses\Jobs\LabelRecognitionRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Queue;

class DispatchAbyssesJob implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  AbyssesJobCreated  $event
      * @return void
      */
    public function handle(AbyssesJobCreated $event)
    {
        if ($event->job->requiresAction()) {
            throw new Exception('Unknown training data method.');
        }  else {
            $request = new LabelRecognitionRequest($event->job);
            Queue::connection(config('abysses.request_connection'))
                ->pushOn(config('abysses.request_queue'), $request);
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  AbyssesJobCreated  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(AbyssesJobCreated $event, $exception)
    {
        $e = new Exception('The Abysses job could not be submitted.');
        Queue::push(new LabelRecognitionFailure($event->job->id, $e));
    }
}
