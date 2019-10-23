<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{config('app.name', 'Tech Bench')}}</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/app.js')}}"></script>
</head>
<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="{{route('dashboard')}}"><img src="{{config('app.logo')}}" class="mr-2" alt="Tech Bench"/></a>
                <a class="navbar-brand brand-logo-mini" href="{{route('dashboard')}}"><img src="{{config('app.logo')}}" alt="Tech Bench"/></a>
            </div>    
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <h2>{{config('app.name', 'Tech Bench')}}</h2>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
<!--               Notifications
                   <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="ti-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                    </li>
-->
                    <li class="nav-item dropdown mr-1">
                        <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="{{route('about')}}">
                            <i class="ti-help-alt mx-0"></i>
                        </a>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="profileDropdown" onclick="return expandProfile();">
                            <img src="img/profile_pic.png" alt="profile"/>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" id="profileDropdownDiv">
                            <a href="{{route('account')}}" class="dropdown-item">
                                <i class="ti-settings text-primary"></i>
                                Settings
                            </a>
                            <a href="{{route('logout')}}" class="dropdown-item">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" onclick="expandNav()">
                    <span class="ti-view-list"></span>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('dashboard')}}">
                            <i class="ti-home menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                   @can('allow_admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.index')}}">
                            <i class="ti-pencil-alt menu-icon"></i>
                            <span class="menu-title">Administration</span>
                        </a>
                    </li>
                    @endcan
                    @can('hasAccess', 'run_reports')
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ti-pencil-alt menu-icon"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                    @endcan
                    @can('hasAccess', 'use_file_links')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('links.index')}}">
                            <i class="ti-link menu-icon"></i>
                            <span class="menu-title">File Links</span>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('customer.index')}}">
                            <i class="ti-user menu-icon"></i>
                            <span class="menu-title">Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('tips.index')}}">
                            <i class="ti-hummer menu-icon"></i>
                            <span class="menu-title">Tech Tips</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; Tech Bench 2016-2020 - All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">@version('full')</span>
                    </div>
                </footer>
            </div>
        </div>
  </div>
</body>
</html>
