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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><strong>Enter your Username and create a Password to finish setting up your account.</strong></div>
                <div class="card-body">
                    {!! Form::open(['route' => ['initialize', $hash]]) !!}
                        {{ Form::bsText('username', 'Username') }}
                        {{ Form::bsPassword('newPass', 'Enter New Password') }}
                        {{ Form::bsPassword('newPass_confirmation', 'Confirm Password') }}
                        {{ Form::bsSubmit('Set Password and Log In') }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
