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
                    <li class="list-group-item"><a href="{{route('admin.user.create')}}" class="text-muted"><i class="fas fa-user-plus"></i> Create New User</a></li>
                    <li class="list-group-item"><a href="{{route('admin.user.index')}}" class="text-muted"><i class="fas fa-user-edit"></i> Modify User</a></li>
                    <li class="list-group-item"><a href="{{route('admin.user.links')}}" class="text-muted"><i class="fas fa-user-tag"></i> User File Links</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Settings:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{route('admin.passwordPolicy')}}" class="text-muted"><i class="fas fa-user-lock"></i> User Password Policy</a></li>
                    <li class="list-group-item"><a href="{{route('admin.roleSettings')}}" class="text-muted"><i class="fas fa-users-cog"></i> User Permissions and Roles</a></li>
                    <li class="list-group-item"><a href="{{route('admin.user.show', 'inactive')}}" class="text-muted"><i class="fas fa-user-slash"></i> View Disabled Users</a></li>
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
                    <li class="list-group-item"><a href="{{route('admin.customerID')}}" class="text-muted"><i class="fas fa-fingerprint"></i> Modify Customer ID</a></li>
                    <li class="list-group-item"><a href="{{route('admin.custFileTypes')}}" class="text-muted"><i class="fas fa-folder"></i> Modify customer file types</a></li>
                    <li class="list-group-item"><a href="{{route('admin.disabledCustomers')}}" class="text-muted"><i class="fas fa-store-alt-slash"></i> View Disabled Customers</a></li>
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
                    <li class="list-group-item"><a href="{{route('admin.categories.index')}}"><i class="fas fa-cog"></i> Equipment Categories</a></li>
                    <li class="list-group-item"><a href="{{route('admin.systems.index')}}"><i class="fas fa-cogs"></i> Equipment Types</a></li>
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
                    <li class="list-group-item"><a href="{{route('admin.logoSettings')}}"><i class="fas fa-image"></i> {{config('app.name')}} Logo</a></li>
                    <li class="list-group-item"><a href="{{route('admin.config')}}"><i class="fas fa-server"></i> {{config('app.name')}} Configuration</a></li>
                    <li class="list-group-item"><a href="{{route('admin.emailSettings')}}"><i class="fas fa-envelope"></i> Email Settings</a></li>
                    <li class="list-group-item"><a href="{{route('admin.module.index')}}"><i class="fas fa-asterisk"></i> {{config('app.name')}} Add-ons</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Maintenance:</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{route('log-viewer::dashboard')}}"><i class="fas fa-bug"></i> Logs</a></li>
                    <li class="list-group-item"><a href="{{route('admin.backups')}}"><i class="fas fa-database"></i> Backups</a></li>
                    <li class="list-group-item"><a href="{{route('admin.updates')}}"><i class="fas fa-level-up-alt"></i> Updates</a></li>
                    {{-- <li class="list-group-item"><a href="#"><i class="ti-hummer"></i> Tools and Extras</a></li> TODO - COMMING SOON!!! --}}
                </ul>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
