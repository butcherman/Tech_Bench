@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h3>User Settings</h3>
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
           <div class="card-body">
               <h4>Basic Settings</h4>
                {!! Form::model($userData, ['route' => ['account', $userID]]) !!}
                    {{ Form::bsText('username', 'Username', null, ['readonly']) }}
                    {{ Form::bsText('first_name', 'First Name') }}
                    {{ Form::bsText('last_name', 'Last Name') }}
                    {{ Form::bsEmail('email', 'Email Address') }}
                    {{ Form::bsSubmit('Update Settings') }}
                {!! Form::close() !!}
           </div>
       </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
       <div class="card">
           <div class="card-body">
               {!! Form::model($userData, ['route' => ['account', $userID], 'class' => 'd-flex flex-column h-100']) !!}
                    <h4>Notification Settings</h4>
                    @method('PUT')
                    <div class="row justify-content-center mt-4">
                        <div class="col-6 col-md-2 order-2 order-md-1">
                            <div class="onoffswitch">
                                <input type="checkbox" name="em_tech_tip" class="onoffswitch-checkbox" id="em_tech_tip" {{ $userSettings->em_tech_tip ? 'checked' : ''}}>
                                <label class="onoffswitch-label" for="em_tech_tip">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8 align-self-center order-1 order-md-2">
                            <h5>Receive Email on New Tech Tip</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-6 col-md-2 order-2 order-md-1">
                            <div class="onoffswitch">
                                <input type="checkbox" name="em_notification" class="onoffswitch-checkbox" id="em_notification" {{ $userSettings->em_notification ? 'checked' : ''}}>
                                <label class="onoffswitch-label" for="em_notification">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8 align-self-center order-1 order-md-2">
                            <h5>Receive Email on System Notification</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-4 mb-4">
                        <div class="col-6 col-md-2 order-2 order-md-1">
                            <div class="onoffswitch">
                                <input type="checkbox" name="auto_del_link" class="onoffswitch-checkbox" id="auto_del_link" {{ $userSettings->auto_del_link ? 'checked' : ''}}>
                                <label class="onoffswitch-label" for="auto_del_link">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8 align-self-center order-1 order-md-2">
                            <h5>Automatically Delete File Links Expired More Than 30 Days</h5>
                        </div>
                    </div>
                    <input class="btn btn-primary btn-block submit-button mt-auto" type="submit" value="Update Notifications">
                {!! Form::close() !!}
           </div>
       </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12 grid-margin stretch-card">
       <div class="card">
           <div class="card-body">
               <div class="row justify-content-center">
                   <div class="col-md-4">
                       <a href="{{route('changePassword')}}" class="btn btn-block btn-primary">Change Password</a>
                   </div>
               </div>
           </div>
        </div>
    </div>
</div>
@endsection
