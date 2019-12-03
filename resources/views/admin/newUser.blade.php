@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Create New User</li>
</ol>
@endsection

@section('content.old')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Create New User</h1></div>
    </div>
</div>
@if(session()->has('success'))
    <div class="row justify-content-center">
        <div class="col-md-8">
            <b-alert variant="success" class="text-center" show dismissible><h3>{{session()->get('success')}}</h3></b-alert>
        </div>
    </div>
@endif
<div class="row justify-content-center">
    <div class="col-md-6">
        {!! Form::open(['route' => 'admin.user.store', 'id' => 'new-user-form']) !!}
            {{Form::bsText('username', 'Username', null, ['autofocus', 'required', 'placeholder' => 'Username Must Be Unique'])}}
            {{Form::bsText('first_name', 'First Name', null, ['required'])}}
            {{Form::bsText('last_name', 'Last Name', null, ['required'])}}
            {{Form::bsEmail('email', 'Email Address', null, ['placeholder' => 'Email Must Be Unique'])}}
            {{Form::bsSelect('role', 'User Role', $roles, '4', ['required'])}}
            {{Form::bsSubmit('Create User and Send Welcome Email')}}
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('content');
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Create New User</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <user-form :role_list="{{json_encode($roles)}}"></user-form>
            </div>
        </div>
    </div>
</div>
@endsection
