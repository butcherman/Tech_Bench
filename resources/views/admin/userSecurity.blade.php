@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>Password Policy</h4>
    </div>
</div>
 <div class="row justify-content-center">
    <div class="col-md-8">
        @if(session()->has('success'))
        <div class="alert alert-success"><h3 class="text-center">{!!session('success')!!}</h3></div>
        @endif
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!!Form::open(['route' => 'admin.passwordPolicy'])!!}
                    <fieldset>
                        <legend>User Passwords</legend>
                        {{Form::bsText('passExpire', 'Password Expires in Days (enter 0 for no expiration)', $passExpire, ['required'])}}
                        {{Form::bsSubmit('Update Password Policy')}}
                    </fieldset>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>

@endsection
