<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tech Bench') }}</title>
    <script>var maxUpload = {{config('filesystems.paths.max_size')}}</script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="{{route('dashboard')}}">Tech Bench</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                    <a class="nav-link" href="{{route('dashboard')}}">
                        <i class="fa fa-fw fa-home"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="File Links">
                    <a class="nav-link" href="{{route('links.index')}}">
                        <i class="fa fa-fw fa-link"></i>
                        <span class="nav-link-text">File Links</span>
                    </a>
                </li>
                @if($current_user->hasAnyRole(['installer', 'admin']))
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Customers">
                        <a class="nav-link" href="{{route('admin.index')}}">
                            <i class="fa fa-fw fa-users"></i>
                            <span class="nav-link-text">Administration</span>
                        </a>
                    </li>
                @endif
                @include('_inc.navbar')
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Customers">
                    <a class="nav-link" href="{{route('customer.index')}}">
                        <i class="fa fa-fw fa-users"></i>
                        <span class="nav-link-text">Customers</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tech Tips">
                    <a class="nav-link" href="{{route('tip.id.index')}}">
                        <i class="fa fa-fw fa-wrench"></i>
                        <span class="nav-link-text">Tech Tips</span>
                    </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Account Settings">
                    <a class="nav-link" href="{{route('account')}}">
                        <i class="fa fa-fw fa-user"></i>
                        <span class="nav-link-text">Account Settings</span>
                    </a>
                </li> 
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
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
<!--
                <li class="nav-item">
                    <a class="nav-link" href="/about" title="help"><i class="fa fa-fw fa-question-circle-o" aria-hidden="true"></i></a>
                </li>
-->
            </ul>
        </div>
    </nav>
    <!--  End Navigation -->
    <!--  Body  -->
    <div class="content-wrapper">
        <div class="container-fluid pad-bottom">
            @yield('breadcrumbs')
            <!--  Primary Page Content  -->
            @yield('content')
        </div>
        <footer class="sticky-footer pad-top">
            <div class="container">
                <div class="text-center">
                    <small>
                        Copyright © Tech Bench 2016-2018 - 
                        Version - @version('version')
                    </small>
                </div>
            </div>
        </footer>
    </div>
    @yield('script')
</body>
</html>
