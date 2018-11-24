@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <img src="/img/{{config('app.logo')}}" alt="Company Logo" class="text-center" id="header-logo" />
            <h1 class="text-center">Tech Bench</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Set Password') }}</div>
                <div class="card-body">
                    @if(count($errors) > 0)
                        <div class="alert alert-danger">
                            There was an issue processing your request<br />
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {!! Form::open(['route' => ['submitInitialize', $hash]]) !!}
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
