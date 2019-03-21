@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item">Select User</li>
</ol>

@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User Accounts</h1></div>
    </div>
</div>
@if(session()->has('success'))
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success">
                <h3 class="text-center">{{session()->get('success')}}</h3>
            </div>
        </div>
    </div>
@endif
<div class="row justify-content-center">
    <div class="col-md-10">
        <user-list
            get_url="{{route('admin.user.show', 'active')}}"
            action_url="{{route($link, ':id')}}"
        ></user-list>
    </div>
</div>
@endsection
