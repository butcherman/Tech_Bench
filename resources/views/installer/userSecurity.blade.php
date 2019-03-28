@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">User Security Settings</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User Security Settings</h1></div>
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
        <div class="col-md-6">
            {!!Form::open(['route' => 'installer.userSecurity'])!!}
                <fieldset>
                    <legend>User Passwords</legend>
                    {{Form::bsText('passExpire', 'Password Expires in Days (enter 0 for no expiration)', $passExpire, ['required'])}}
                    {{Form::bsSubmit('Update Security Settings')}}
                </fieldset>
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
