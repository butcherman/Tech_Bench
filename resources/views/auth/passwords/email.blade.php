@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-8 col-xl-6">
        <div class="row" id="header-title">
            <div class="col-12"><h1>Password Request</h1></div>
        </div>
        <div class="row row-eq-height justify-content-center align-items-center login-form-sub-container">
            <div class="col-md-6">
                <img src="{{config('app.logo')}}" alt="Company Logo" id="header-logo" />
            </div>
            <div class="col-md-6">
               @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @else
                    <p>Enter your email address for instructions for accessing your account.</p>
                    {!! Form::open(['route' => 'password.email', 'id' => 'password-reset-form']) !!}
                        {{Form::bsEmail('email', null, null, ['placeholder' => 'Enter Your Email Address', 'autofocus'])}}
                        {{Form::bsSubmit('Send Password Reset Instructions')}}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
