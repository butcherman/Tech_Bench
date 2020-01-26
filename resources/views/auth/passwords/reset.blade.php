@extends('layouts.guest')

@section('content')  
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-8 col-xl-6">
        <div class="row" id="header-title">
            <div class="col-12"><h1>Reset Password</h1></div>
        </div>
        <div class="row row-eq-height justify-content-center align-items-center login-form-sub-container">
            <div class="col-md-6">
                <img src="{{config('app.logo')}}" alt="Company Logo" id="header-logo" />
            </div>
            <div class="col-md-6">
               @if($errors->any())
                   @foreach($errors->all() as $err)
                       <h3>{{$err}}</h3>
                    @endforeach
                @endif
                {!! Form::open(['route' => 'password.request', 'id' => 'password-reset-confirm']) !!}
                    <input type="hidden" name="token" value="{{ $token }}">
                    {{Form::bsEmail('email', 'Email', null, ['placeholder' => 'Enter Your Email Address', 'autofocus'])}}
                    {{Form::bsPassword('password', 'Password', null, ['placeholder' => 'Enter A New Password'])}}
                    {{Form::bsPassword('password_confirmation', 'Confirm Password', null, ['placeholder' => 'Confirm Your New Password'])}}
                    {{Form::bsSubmit('Reset Password')}}
                {!! Form::close() !!}  
            </div>
        </div>
    </div>
</div>
@endsection
