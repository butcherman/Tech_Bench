@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">System Administration</li>
</ol>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>System Administration</h1></div>
        </div>
    </div>
    @if(session()->has('success'))
        <div class="row justify-content-center">
            <div class="col-md-8">
                <b-alert variant="success" class="text-center" show dismissible><h3>{{session()->get('success')}}</h3></b-alert>
            </div>
        </div>
    @endif
    @if(Auth::user()->hasAnyRole(['installer', 'admin']))
        <div class="row pad-top justify-content-center">
            <div class="col-md-4">
                <h4 class="pl-5">Users:</h4>
                <ul class="admin-list">
                    <li><a href="{{route('admin.user.create')}}">Create New User</a></li>
                    <li><a href="{{route('admin.user.index')}}">Update Existing User</a></li>
                    <li><a href="{{route('admin.password')}}">Reset Users Password</a></li>
                    <li><a href="{{route('admin.disable')}}">Disable User</a></li>
                    <li><a href="{{route('admin.links')}}">User File Links</a></li>
        @if(Auth::user()->hasAnyRole(['installer']))
                    <li><a href="{{route('installer.userSecurity')}}">User Security Settings</a></li>
        @endif
                </ul>
            </div>
{{--
            <div class="col-md-4">
                <h4 class="pl-5">Customers:</h4>
                <ul class="admin-list">
                    <li><a href="">Modify Customer ID</a></li>
                    <li><a href="">Disable Customer</a></li>
                    <li><a href="">View Disabled Customers</a></li>
                </ul>
            </div>
--}}
    @endif
    @if(Auth::user()->hasAnyRole(['installer']))
            <div class="col-md-4">
                <h4 class="pl-5">System Settings:</h4>
                <ul class="admin-list">
                    <li><a href="{{route('installer.customize')}}">Timezone and Logo</a></li>
                    <li><a href="{{route('installer.emailSettings')}}">Email Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="row pad-top justify-content-center">
            <div class="col-md-4">
                <h4 class="pl-5">Categories and Systems</h4>
                <ul class="admin-list">
                    <li><a href="{{route('installer.categories.create')}}">Create New Category</a></li>
                    <li><a href="{{route('installer.categories.index')}}">Modify Existing Category</a></li>
                    <li><a href="{{route('installer.systems.create')}}">Create New System</a></li>
                    <li><a href="{{route('installer.systems.index')}}">Modify Existing System</a></li>
                </ul>
            </div>
{{--
            <div class="col-md-4">
                <h4 class="pl-5">System Maintenance</h4>
                <ul class="admin-list">
                    <li><a href="">System Logs</a></li>
                    <li><a href="">System Backup</a></li>
                    <li><a href="">Update System</a></li>
                    <li><a href="">Tools and Extras</a></li>
                </ul>
            </div>
--}}
    @endif
        </div>
@endsection