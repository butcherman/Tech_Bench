<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>var maxUpload = {{env('MAX_UPLOAD')}}</script>
  
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/dropzone/dropzone.js') }}"></script>
    <link href="{{ asset('js/dropzone/dropzone.css') }}" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="/dashboard">Tech Bench</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                    <a class="nav-link" href="/dashboard">
                        <i class="fa fa-fw fa-home"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                @if($current_user->hasAnyRole(['installer', 'admin', 'report']))
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Administration">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseAdmin" data-parent="#exampleAccordion">
                            <i class="fa fa-fw fa-wrench"></i>
                            <span class="nav-link-text">Administration</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="collapseAdmin">
                            @if($current_user->hasAnyRole('installer'))
                                <li>
                                    <a href="/installer">Site Administration</a>
                                </li>
                            @endif
                            @if($current_user->hasAnyRole(['installer', 'admin']))
                                <li>
                                    <a href="/admin">Administration</a>
                                </li>
                            @endif
                            <li>
                                <a href="/reports">Reports</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @include('_inc.navbar')
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Customers">
                    <a class="nav-link" href="/customer">
                        <i class="fa fa-fw fa-users"></i>
                        <span class="nav-link-text">Customers</span>
                    </a>
                </li>
<!--
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tech Tips">
                    <a class="nav-link" href="/tips">
                        <i class="fa fa-fw fa-wrench"></i>
                        <span class="nav-link-text">Tech Tips</span>
                    </a>
                </li>
-->
<!--
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Files">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseFiles" data-parent="#exampleAccordion">
                        <i class="fa fa-fw fa-file"></i>
                        <span class="nav-link-text">Files</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseFiles">
                        <li>
                            <a href="/links">File Links</a>
                        </li>
                        <li>
                            <a href="/company-files">Company Files</a>
                        </li>
                        <li>
                            <a href="/my-files">My Files</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Account Settings">
                    <a class="nav-link" href="/account">
                        <i class="fa fa-fw fa-user"></i>
                        <span class="nav-link-text">Account Settings</span>
                    </a>
                </li> 
-->
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
<!--                        Search Feature to be added later
                    <form class="form-inline my-2 my-lg-0 mr-lg-2">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Search for...">
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
-->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        <i class="fa fa-fw fa-sign-out"></i>
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about" title="help"><i class="fa fa-fw fa-question-circle-o" aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <!--  End Navigation -->
    <!--  Body  -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
<!--
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">My Dashboard</li>
            </ol>
-->
            <!--  Primary Page Content  -->
            @yield('content')
        </div>
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Copyright © Tech Bench 2016-2018</small>
                </div>
            </div>
        </footer>
    </div>
    @yield('script')
</body>
</html>
