<div class="sidebar-tab__content sidebar-tab__content--abysses">
    <div class="abysses-tab-content__top">
        <p>
            Job #{{$job->id}} @include('abysses::mixins.job-state', ['job' => $job])
        </p>
        <p>
            created <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}} by {{$job->user->firstname}} {{$job->user->lastname}}</span>
        </p>
    </div>
    <div class="abysses-tab-content__bottom">
        <form class="text-right" action="{{ url("api/v1/abysses-jobs/{$job->id}") }}" method="POST" onsubmit="return confirm('Are you sure that you want to delete this job?')">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            @if ($job->state_id === $states['label-recognition'])
                <button class="btn btn-danger" type="button" title="The job cannot be deleted while the label recognition is running" disabled>Delete this job</button>
            {{-- The array key is instance-segmentation for legacy reasons --}}
            @else
                <button class="btn btn-danger" type="submit">Delete this job</button>
            @endif
        </form>
    </div>
    <div class="abysses-tab-content__bottom">
        <form class="text-right" action="{{ route('abysses-download', ['jobId' => $job->id]) }}" method="POST">
            @csrf
            @if ($job->state_id === $states['success'])
                <button class="btn btn-danger" type="submit">Download results</button>
            @endif
        </form>
    </div>
</div>
