@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Email Settings</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customize Email Settings</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session()->has('success'))
            <div class="alert alert-success">{!!session('success')!!}</div>
        @endif
    </div>
</div>
<div class="row justify-content-center pad-top">
    <div class="col-md-8">
        <div class="alert alert-danger d-none" id="failed-test"><h5></h5></div>
        <div class="alert alert-success d-none" id="successful-test"><h5 class="text-center">Email Sent Successfully</h5></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        <test-email
            submit_route="{{route('installer.emailSettings')}}"
            :current_settings="{
                host: '{{config('mail.host')}}',
                port: '{{config('mail.port')}}',
                encryption: '{{config('mail.encryption')}}',
                username: '{{config('mail.username')}}',
                password: '{{config('mail.password')}}',
            }"
        ></test-email>
    </div>
</div>
@endsection
