@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Reset Password For {{$user}}</h1></div>
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
            {!! Form::open(['route' => ['admin.resetPass', $id], 'id' => 'reset-password-form']) !!}
                {{Form::bsPassword('password', 'New Password', null, ['required'])}}
                {{Form::bsPassword('password_confirmation', 'Confirm Password', null, ['required'])}}
                {{Form::bsSubmit('Update Password')}}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
