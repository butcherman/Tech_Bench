@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>System Administration</h4>
    </div>
</div>
@if(session()->has('success'))
<div class="row justify-content-center">
    <div class="col-md-8">
        <b-alert variant="success" class="text-center" show dismissible><h3>{{session()->get('success')}}</h3></b-alert>
    </div>
</div>
@endif
<div class="row justify-content-center">
    @can('hasAccess', 'Manage Users')
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Users:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{route('admin.user.create')}}"><i class="ti-user"></i> Create New User</a></li>
                    <li class="list-group-item"><a href="{{route('admin.user.index')}}"><i class="ti-wand"></i> Modify User</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-link"></i> User File Links</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Settings:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="#"><i class="ti-settings"></i> User Password Policy</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-direction-alt"></i> User Permissions</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-na"></i> View Disabled Users</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-eraser"></i> Re-enable Disabled User</a></li>
                </ul>
            </div>
        </div>
    </div>
    @endcan
    @can('hasAccess', 'Manage Customers')
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Customers:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="#"><i class="ti-server"></i> Modify Customer ID</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-eraser"></i> View Disabled Customers</a></li>
                </ul>
            </div>
        </div>
    </div>
    @endcan
</div>
<div class="row justify-content-center">
    @can('hasAccess', 'Manage Equipment')
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Equipment:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="#"><i class="ti-layers"></i> Equipment Categories</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-layers-alt"></i> Equipment Types</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-desktop"></i> Equipment Information for Customers</a></li>
                </ul>
            </div>
        </div>
    </div>
    @endcan
    @can('is_installer')
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{config('app.name')}} Settings:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="#"><i class="ti-image"></i> {{config('app.name')}} Logo</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-server"></i> {{config('app.name')}} Configuration</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-email"></i> Email Settings</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-dashboard"></i> {{config('app.name')}} Add-ons</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Maintenance:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="#"><i class="ti-write"></i> Logs</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-harddrives"></i> Backups</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-support"></i> Updates</a></li>
                    <li class="list-group-item"><a href="#"><i class="ti-hummer"></i> Tools and Extras</a></li>
                </ul>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
