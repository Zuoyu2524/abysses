@extends('app')
@section('title', "ABYSSES job #{$job->id}")
@section('full-navbar', true)

@push('styles')
<link href="{{ cachebust_asset('vendor/largo/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('../assets/sass/main.scss') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ cachebust_asset('vendor/largo/scripts/main.js') }}"></script>
<script src="{{ cachebust_asset('../assets/js/main.js') }}"></script>
<script type="text/javascript">
    biigle.$declare('abysses.job', {!! $job->toJson() !!});
    biigle.$declare('abysses.states', {!! $states->toJson() !!});
    biigle.$declare('abysses.images', {!! $images->toJson() !!});
    biigle.$declare('abysses.imageFileUri', '{!! url('api/v1/images/:id/file') !!}');
    biigle.$declare('abysses.tpUrlTemplate', '{{$tpUrlTemplate}}');
</script>
@endpush

@section('content')

<!-- 为 div 元素设置 id="app" 属性使 Vue 对象实例成功挂载上来 -->
<div id="app" class="flex-center position-ref full-height">
            <p>Hello</p>
            <div class="content">
                <p>coming</p>
                <!-- 引入 hello-component 组件进行渲染 -->
                <image-gallery></image-gallery>
            </div>
        </div>
        <!-- 最后不要忘了引入包含 Vue 框架和 Vue 组件的 app.js 文件 -->
        <script src="{{ asset('../assets/js/main.js') }}"></script>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes.partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-abysses', $volume->id)}}">ABYSSES</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
