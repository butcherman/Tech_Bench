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
                <div class="card-header">
                    <strong>Reset Password</strong>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    {!! Form::open(['route' => 'password.email', 'id' => 'password-reset-form']) !!}
                        <div class="row justify-content-center">
                            <div class="col-8">
                                {{Form::bsEmail('email', 'Email', null, ['placeholder' => 'Enter Your Email Address'])}}
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-6">
                                {{Form::bsSubmit('Send Password Reset Link')}}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
