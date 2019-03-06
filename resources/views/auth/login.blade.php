@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <img src="{{config('app.logo')}}" alt="Company Logo" class="text-center" id="header-logo" />
            <h1 class="text-center">Tech Bench</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <strong>Login</strong>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'login', 'id' => 'user-login-form']) !!}
                        <div class="row justify-content-center">
                            <div class="col-8">
                                {{ Form::bsText('username', 'Username', null, ['placeholder' => 'Enter Username', 'required', 'autofocus']) }}
                                {{ Form::bsPassword('password', 'Password', null, ['placeholder' => 'Enter Password', 'required']) }}
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-md-4">
                                {{ Form::bsCheckbox('remember', 'Remember Me') }}
                            </div>
                        </div> 
                        <div class="form-group row justify-content-center mb-0">
                            <div class="col-md-8">
                                {{ Form::bsSubmit('Login') }}
                            </div>
                        </div>
                        <div class="form-group row justify-content-center mb-0">
                            <div class="col-md-8 text-center">
                                <a class="btn btn-link" href="{{ route('password.request') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
