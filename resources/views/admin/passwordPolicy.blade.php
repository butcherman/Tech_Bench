@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>Password Policy</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <password-policy :expire="{{config('auth.passwords.settings.expire')}}"></password-policy>
            </div>
        </div>
    </div>
</div>
@endsection
