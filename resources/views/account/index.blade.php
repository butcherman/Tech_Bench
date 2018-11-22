@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">My Dashboard</a></li>
    <li class="breadcrumb-item active">Account Settings</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User Settings</h1></div>
        </div>
    </div>
    @if(session()->has('success'))
        <div class="row justify-content-center">
            <div class="col-md-8 alert-success">
                <h2 class="text-center">{{ session('success') }}</h2>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="row justify-content-center">
            <div class="col-md-8  alert-danger">
                @foreach($errors->all() as $err)
                    <h2 class="text-center">{{ $err }}</h2>
                @endforeach
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!! Form::model($userData, ['route' => ['submitAccount', $userID]]) !!}
                {{ Form::bsText('username', 'Username', null, ['readonly']) }}
                {{ Form::bsText('first_name', 'First Name') }}
                {{ Form::bsText('last_name', 'Last Name') }}
                {{ Form::bsEmail('email', 'Email Address') }}
                <div class="row justify-content-center">
                    <div class="col-8">
                        <label class="switch">
                            <input type="checkbox" name="em_tech_tip" {{ $userSettings->em_tech_tip ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        Receive Email on New Tech Tip 
                    </div>
                </div>
<!--
                <div class="row justify-content-center">
                    <div class="col-8">
                        <label class="switch">
                            <input type="checkbox" name="em_file_link" {{ $userSettings->em_file_link ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        Receive Email on Uploaded File to File Link 
                    </div>
                </div>
-->
                <div class="row justify-content-center">
                    <div class="col-8">
                        <label class="switch">
                            <input type="checkbox" name="em_notification" {{ $userSettings->em_notification ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        Receive Email on System Notification
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8">
                        <label class="switch">
                            <input type="checkbox" name="auto_del_link" {{ $userSettings->auto_del_link ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        Automatically Delete File Links Expired More Than 30 Days
                    </div>
                </div>
                <h2 class="text-center"><a href="{{route('changePassword')}}">Change Password</a></h2>
                {{ Form::bsSubmit('Update Settings') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
