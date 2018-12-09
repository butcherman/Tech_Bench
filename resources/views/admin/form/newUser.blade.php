@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.users.index')}}">Users</a></li>
    <li class="breadcrumb-item active">Create User</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Create New User</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <h5 class="text-center">Error: {{ $error }}</h5>
                    @endforeach
                </div>
            @endif
            {!! Form::open(['route' => 'admin.users.store', 'id' => 'new-user-form']) !!}
                {{Form::bsText('username', 'Username', null, ['autofocus', 'required'])}}
                {{Form::bsText('first_name', 'First Name', null, ['required'])}}
                {{Form::bsText('last_name', 'Last Name', null, ['required'])}}
                {{Form::bsEmail('email', 'Email Address')}}
                {{ Form::bsSelect('role', 'User Role', $roles, '4', ['required']) }}
                {{Form::bsSubmit('Create User and Send Welcome Email')}}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
