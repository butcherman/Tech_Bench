@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">My Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('account')}}">Account Settings</a></li>
    <li class="breadcrumb-item active">Change Password</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User Settings</h1></div>
        </div>
    </div>
    @if(session()->has('change_password'))
        <div class="row justify-content-center pt-5 pb-5">
            <div class="col-md-8 alert-info pt-3 pb-3">
                <h2 class="text-center">Your Password Has Expired<br />Please Enter a New Password</h2>
            </div>
        </div>
    
    @endif
    @if(session()->has('success'))
        <div class="row justify-content-center">
            <div class="col-md-8 alert-success">
                <h2 class="text-center">{{ session('success') }}</h2>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row justify-content-center">
            <div class="col-md-8  alert-danger">
                <h2 class="text-center">{{ session('error') }}</h2>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!! Form::open(['route' => 'changePassword']) !!}
                {{ Form::bsPassword('oldPass', 'Enter Current Password') }}
                {{ Form::bsPassword('newPass', 'Enter New Password') }}
                {{ Form::bsPassword('newPass_confirmation', 'Confirm Password') }}
                {{ Form::bsSubmit('Change Password') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
