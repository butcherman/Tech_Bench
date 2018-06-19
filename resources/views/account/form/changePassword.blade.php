@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User Settings</h1></div>
        </div>
    </div>
     @if(session()->has('success'))
        <div class="row justify-content-center">
            <div class="col-md-8 alert-success">
                <h2 class="text-center">{{ session('success') }}</h2>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="row justify-content-center">
            <div class="col-md-8  alert-danger">
                @foreach($errors->all() as $err)
                    <h2 class="text-center">{{ $err }}</h2>
                @endforeach
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row justify-content-center">
            <div class="col-md-8  alert-danger">
                <h2 class="text-center">{{ session('error') }}</h2>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!! Form::open(['route' => 'submitPassword']) !!}
                {{ Form::bsPassword('oldPass', 'Enter Current Password') }}
                {{ Form::bsPassword('newPass', 'Enter New Password') }}
                {{ Form::bsPassword('newPass_confirmation', 'Confirm Password') }}
                {{ Form::bsSubmit('Change Password') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
