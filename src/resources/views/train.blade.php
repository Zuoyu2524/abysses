@extends('app')
@section('title', "ABYSSES job #{$job->id}")
@section('full-navbar', true)

@push('styles')
<link href="{{ cachebust_asset('vendor/largo/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/zuoyu2524/abysses/styles/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ cachebust_asset('vendor/largo/scripts/main.js') }}"></script>
<script src="{{ cachebust_asset('vendor/zuoyu2524/abysses/scripts/main.js') }}"></script>
<script type="text/javascript">
    biigle.$declare('abysses.job', {!! $job->toJson() !!});
    biigle.$declare('abysses.states', {!! $states->toJson() !!});
    biigle.$declare('abysses.labelTrees', {!! $labels->toJson() !!});
    biigle.$declare('abysses.imageFileUri', '{!! url('api/v1/images/:id/file') !!}');
    biigle.$declare('abysses.tpUrlTemplate', '{{$tpUrlTemplate}}');
</script>
@endpush



@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <img src="abysses.imageFileUri">
        </div>
        <div class="col-lg-3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
			    <div class="form-group">
				<label for="tag">Please select the correct labels:</label>
				<select class="form-control" id="tag" name="tag">
				    <option value="">please select</option>
				    @foreach ($labels as $label)
				        <option value="{{ $label->id }}">{{ $label->name }}</option>
				    @endforeach
				</select>
			    </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>



@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes.partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-abysses', $volume->id)}}">ABYSSES</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
