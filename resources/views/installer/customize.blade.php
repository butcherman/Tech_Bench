@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Customize Tech Bench</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customize Tech Bench</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session()->has('success'))
            <div class="alert alert-success"><h3 class="text-center">{!!session('success')!!}</h3></div>
        @endif
    </div>
</div>
<div class="row justify-content-center pad-top mb-5">
    <div class="col-md-4">
        {!!Form::open(['route' => 'installer.customize'])!!}
            {{Form::bsText('url', 'Website URL', config('app.url'), ['required readonly'])}}
            {{Form::bsTimeZone()}}
            {{Form::bsSubmit('Update Tech Bench')}}
        {!!Form::close()!!}
    </div>
</div>
<logo-replace logo="{{config('app.logo')}}" submit_route="{{route('installer.submitLogo')}}"></logo-replace>
@endsection
