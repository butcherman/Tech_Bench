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
                <h5 class="text-center"><strong>Enter your Username and create a Password to finish setting up your account.</strong></h4>
                {!! Form::open(['route' => ['initialize', $hash]]) !!}
                    {{ Form::bsText('username', 'Username', null, ['placeholder' => 'Username', 'autofocus']) }}
                    {{ Form::bsPassword('newPass', 'Enter New Password', null, ['placeholder' => 'Password']) }}
                    {{ Form::bsPassword('newPass_confirmation', 'Confirm Password', null, ['placeholder' => 'Confirm Password']) }}
                    {{ Form::bsSubmit('Set Password and Log In') }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
