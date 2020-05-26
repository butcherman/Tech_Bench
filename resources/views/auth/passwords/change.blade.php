@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Change Password</h4>
    </div>
</div>
@if(session()->has('change_password'))
    <div class="row justify-content-center pt-5 pb-5">
        <div class="col-md-8 alert-info pt-3 pb-3">
            <h2 class="text-center">Your Password Has Expired<br />Please Enter a New Password</h2>
        </div>
    </div>
@endif
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <change-password></change-password>
            </div>
        </div>
    </div>
</div>
@endsection
