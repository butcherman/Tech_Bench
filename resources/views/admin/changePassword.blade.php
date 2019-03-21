@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">Select User</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.user.edit', $id)}}">{{$user}}</a></li>
    <li class="breadcrumb-item active">Reset Password</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Reset Password For {{$user}}</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!! Form::open(['route' => ['admin.changePassword', $id], 'id' => 'reset-password-form']) !!}
                {{Form::bsPassword('password', 'New Password', null, ['required'])}}
                {{Form::bsPassword('password_confirmation', 'Confirm Password', null, ['required'])}}
                <div class="row justify-content-center">
                    <div class="col-8">
                        <label class="switch">
                            <input type="checkbox" name="force_change" checked>
                            <span class="slider round"></span>
                        </label>
                        <strong>Force Password Change on Next Logon</strong>
                    </div>
                </div>
                {{Form::bsSubmit('Update Password')}}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
