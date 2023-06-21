<div class="list-group">
    @foreach ($jobs as $job)
        @if ($job->requiresAction())
             <meta http-equiv="refresh" content="0; url={{ route('abysses-train', $job->id) }}">
        @else
            Job #{{ $job->id }} created <span title="{{ $job->created_at->toIso8601String() }}">{{ $job->created_at->diffForHumans() }}</span>
            <span class="pull-right">@include('abysses::mixins.job-state', ['job' => $job])</span>
        @endif
    @endforeach
</div>

