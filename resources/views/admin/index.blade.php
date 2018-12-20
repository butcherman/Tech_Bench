@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">System Administration</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>System Administration</h1></div>
        </div>
    </div>
    @if($current_user->hasAnyRole(['installer', 'admin']))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
<!--                        Users and Customers-->
                        Users
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="{{route('admin.users.create')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-user-plus"></i>
                                        </div>
                                        Create New User
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="{{route('admin.users.index')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-user"></i>
                                        </div>
                                        User Accounts
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="{{route('admin.links')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-link"></i>
                                        </div>
                                        User File Links
                                    </a>
                                </div>
                            </div>
                            @if($current_user->hasAnyRole(['installer']))
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary 0-hidden h-100">
                                        <a href="{{route('installer.userSettings')}}" class="card-body text-white">
                                            <div class="card-body-icon">
                                                <i class="fa fa-fw fa-lock"></i>
                                            </div>
                                            User Security Settings
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
<!--
                        <div class="row justify-content-center pad-top">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="#" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-street-view"></i>
                                        </div>
                                        Modify A Customer ID Number
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="#" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-street-view"></i>
                                        </div>
                                        Deactivated Customers
                                    </a>
                                </div>
                            </div>
                        </div>
-->
                    </div>
                </div>
            </div>
        </div>          
    @endif
    @if($current_user->hasAnyRole(['installer']))
        <div class="row justify-content-center pad-top">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        System Settings
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center pad-top">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="{{route('installer.customize')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-clock-o"></i>
                                        </div>
                                        Timezone and Logo
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-primary 0-hidden h-100">
                                    <a href="{{route('installer.email')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-envelope"></i>
                                        </div>
                                        Email Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <div class="row justify-content-center pad-top">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Categories and Systems
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="card text-white bg-info 0-hidden h-100">
                                    <a href="{{route('installer.system-categories.create')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-cog"></i>
                                        </div>
                                        Create New Category
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-info 0-hidden h-100">
                                    <a href="{{route('installer.system-categories.index')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-cog"></i>
                                        </div>
                                        Edit A Category
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-info 0-hidden h-100">
                                    <a href="{{route('installer.systems.index')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-cog"></i>
                                        </div>
                                        Create New System
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-info 0-hidden h-100">
                                    <a href="{{route('installer.systems.show', ['id' => 'select'])}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-cog"></i>
                                        </div>
                                        Edit a System
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        System Maintenance
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="card text-white bg-warning 0-hidden h-100">
                                    <a href="{{route('installer.logs')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-list"></i>
                                        </div>
                                        System Logs
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning 0-hidden h-100">
                                    <a href="{{route('installer.backup')}}" class="card-body text-white">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-list"></i>
                                        </div>
                                        Backups
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
