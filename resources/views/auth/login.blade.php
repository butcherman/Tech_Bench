@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-8 col-xl-6">
        <div class="row" id="header-title">
            <div class="col-12"><h1>{{config('app.name', 'Tech Bench')}}</h1></div>
        </div>
        <div class="row row-eq-height justify-content-center align-items-center login-form-sub-container">
            <div class="col-md-6">
                <img src="{{config('app.logo')}}" alt="Company Logo" id="header-logo" />
            </div>
            <div class="col-md-6">
                {!! Form::open(['route' => 'login', 'id' => 'user-login-form']) !!}
                    {{ Form::bsText('username', null, null, ['placeholder' => 'Username', 'autofocus']) }}
                    {{ Form::bsPassword('password', null, null, ['placeholder' => 'Password']) }}
                    {{ Form::bsCheckbox('remember', 'Remember Me') }}
                    {{ Form::bsSubmit('Login') }}
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
@endsection
