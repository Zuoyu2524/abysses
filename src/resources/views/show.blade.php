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
@endpush

@section('content')
<button type="button" class="btn btn-primary">Click Me</button>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes.partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-abysses', $volume->id)}}">ABYSSES</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
