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
    biigle.$declare('abysses.labelTrees', {!! $trees->toJson() !!});
    biigle.$declare('annotations.imageFileUri', '{!! url('api/v1/images/:id/file') !!}');
    biigle.$declare('abysses.tpUrlTemplate', '{{$tpUrlTemplate}}');
</script>
@endpush

@section('content')
<div id="abysses-show-container" class="sidebar-container">
    <div class="sidebar-container__content">
        <div v-show="infoTabOpen" class="abysses-content">
            @include('abysses::show.info-content')
        </div>
        @if ($job->state_id == $states['retraining-proposals'])
            <div v-show="selectProposalsTabOpen" class="abysses-content">
                @include('abysses::show.select-labels-content')
            </div>
            <div v-show="refineProposalsTabOpen" class="abysses-content">
                @include('abysses::show.refine-proposals-content')
            </div>
        @endif
        <loader-block :active="loading"></loader-block>
    </div>
    <sidebar v-bind:open-tab="openTab" v-on:open="handleTabOpened" v-on:toggle="handleSidebarToggle">
        <sidebar-tab name="info" icon="info-circle" title="Job information">
            @include('abysses::show.info-tab')
    </sidebar-tab>
    </sidebar>
</div>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
@include('volumes.partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-abysses', $volume->id)}}">ABYSSES</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
