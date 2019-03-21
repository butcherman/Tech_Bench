@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">Select User</a></li>
    <li class="breadcrumb-item active">Edit {{$user->first_name}} {{$user->last_name}}</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Edit User</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        {!! Form::model($user, ['route' => ['admin.user.update', $userID], 'id' => 'new-user-form']) !!}
            @method('PUT')
            {{Form::bsText('username', 'Username', null, ['autofocus', 'required'])}}
            {{Form::bsText('first_name', 'First Name', null, ['required'])}}
            {{Form::bsText('last_name', 'Last Name', null, ['required'])}}
            {{Form::bsEmail('email', 'Email Address')}}
            {{Form::bsSelect('role', 'User Role', $roles, $role, ['required'])}}
            {{Form::bsSubmit('Update User')}}
        {!! Form::close() !!}
    </div>
</div>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <h3 class="text-center"><a href="{{route('admin.changePassword', $user->user_id)}}">Change Password for {{$user->first_name}} {{$user->last_name}}</a></h3>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="text-center"><a href="{{route('admin.confirmDisable', $user->user_id)}}">Disable {{$user->first_name}} {{$user->last_name}}</a></h3>
    </div>
</div>
@endsection
