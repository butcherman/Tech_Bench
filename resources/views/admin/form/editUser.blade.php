@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Edit User</h1></div>
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
            {!! Form::model($user, ['route' => ['admin.users.update', $userID], 'id' => 'new-user-form']) !!}
                @method('PUT')
                {{Form::bsText('username', 'Username', null, ['autofocus', 'required'])}}
                {{Form::bsText('first_name', 'First Name', null, ['required'])}}
                {{Form::bsText('last_name', 'Last Name', null, ['required'])}}
                {{Form::bsEmail('email', 'Email Address')}}
                {{ Form::bsSelect('role', 'User Role', $roles, $role, ['required']) }}
                {{Form::bsSubmit('Update User')}}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
