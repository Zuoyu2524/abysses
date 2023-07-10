<div class="abysses-content-message">
    @if ($job->state_id === $states['success'])
        <div class="abysses-status">
            <span class="fa-stack fa-2x" title="Job finished">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        <p class="text-success text-center lead">
            This job is finished.<br>Review the labels <i class="fas fa-check-square"></i> to create the your annotations.
        </p>
    @elseif ($job->hasFailed())
        <div class="abysses-status abysses-status--failed">
            <span class="fa-stack fa-2x" title="Job failed">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        <p class="text-danger lead">
            @if ($job->state_id === $states['failed-label-recognition'])
                The job has failed during label recognition.
            @else
                The job has failed during retraining process.
            @endif
        </p>
        @if (($user->can('sudo') || config('app.debug')) && array_key_exists('message', $job->error))
            <pre style="max-width: 90%;max-height: 50%" v-pre>{{$job->error['message']}}</pre>
        @endif
    @else
        <div class="abysses-status abysses-status--running">
            <span class="fa-stack fa-2x" title="Job in progress">
                @if ($job->isRunning())
                    <i class="fas fa-circle fa-stack-1x"></i>
                    <i class="fas fa-cog fa-spin fa-stack-2x"></i>
                @else
                    <i class="fas fa-circle fa-stack-2x"></i>
                @endif
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        @if ($job->state_id === $states['label-recognition'])
            <p class="text-warning text-center lead">
                Label recognition in progress.<br>Please come back later.
            </p>
        @else
            <p class="text-warning text-center lead">
                Recognition retraining in progress.<br>Please come back later.
            </p>
        @endif
    @endif
</div>
