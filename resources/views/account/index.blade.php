@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>User Settings</h4>
    </div>
</div>
@if(session()->has('success'))
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="alert alert-success text-center">
            <h3>{{session('success')}}</h3>
        </div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
       <div class="card">
           <div class="card-header">Basic Settings</div>
           <div class="card-body">
                <user-profile :user_data="{{json_encode($userData)}}"></user-profile>
           </div>
       </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
       <div class="card">
           <div class="card-header">Notification Settings</div>
           <div class="card-body">
                <user-settings :user_settings="{{json_encode($userSettings)}}"></user-settings>
           </div>
       </div>
    </div>
</div>
@endsection
