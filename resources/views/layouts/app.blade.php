<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name', 'Tech Bench')}}</title>

    <!-- Scripts -->
    @routes
    <script>
        window.techBench = {
            'maxUpload': '{{config('filesystems.paths.max_size')}}',
            'csrfToken': '{{csrf_token()}}'
        };
    </script>
    <script src="{{mix('js/app.js')}}"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>

<body>
    <div class="container-scroller" id="app">
        <nav class="navbar top-navbar fixed-top">
            <div class="navbar-logo-wrapper d-flex">
                <a class="navbar-logo" href="{{route('dashboard')}}"><img src="{{config('app.logo')}}" class="mr-2" alt="Tech Bench"/></a>
            </div>
            <div class="navbar-brand d-none d-md-flex">
                <h2>{{config('app.name', 'Tech Bench')}}</h2>
            </div>
            <div class="navbar-data">
                {{-- <a href="#" class="text-muted" title="Help" v-b-tooltip.hover>
                    <i class="far fa-question-circle"></i>
                </a> --}}
                <a href="{{route('about')}}" class="text-muted" title="About {{config('app.name')}}" v-b-tooltip.hover>
                    <i class="fas fa-info-circle"></i>
                </a>
                <logout></logout>
                <button class="navbar-toggler d-lg-none" type="button" onclick="expandNav()">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{route('settings')}}" title="Settings" v-b-tooltip.hover>
                    <b-avatar variant="warning" text="{{Auth::user()->initials}}"></b-avatar>
                </a>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-nav" id="side-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('dashboard')}}">
                            <i class="fas fa-tachometer-alt menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    @can('allow_admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.index')}}">
                            <i class="fas fa-user-shield menu-icon"></i>
                            <span class="menu-title">Administration</span>
                        </a>
                    </li>
                    @endcan
                    @can('hasAccess', 'Run Reports')
                    <li class="nav-itema">
                        <a class="nav-link" href="#">
                            <i class="fas fa-book"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                    @endcan
                    @can('hasAccess', 'Use File Links')
                    <li class="nav-itema">
                        <a class="nav-link" href="#">
                            <i class="fas fa-link menu-icon"></i>
                            <span class="menu-title">File Links</span>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('customer.index')}}">
                            <i class="fas fa-user-tie menu-icon"></i>
                            <span class="menu-title">Customers</span>
                        </a>
                    </li>
                    <li class="nav-itema">
                        <a class="nav-link" href="#">
                            <i class="fas fa-tools menu-icon"></i>
                            <span class="menu-title">Tech Tips</span>
                        </a>
                    </li>
                    @foreach($tb_modules as $mod)
                    <li class="nav-itema">
                        <a class="nav-link" href="{{route('index').'/'.$mod->getLowerName()}}">
                            {{-- <i class="fas fa-asterisk menu-icon"></i> --}}
                            <i class="{{config($mod->getLowerName().'.icon')}} menu-icon"></i>
                            <span class="menu-title">{{preg_replace('/(.*?[a-z]{1})([A-Z]{1}.*?)/', '${1} ${2}', $mod->getName())}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </nav>
            <div class="content">
                <div class="content-wrapper">
                    @yield('content')
                    <axios-error></axios-error>
                </div>
                <footer class=" footer page-footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; {{config('app.copyright')}}<span class="d-none d-md-inline"> - All rights reserved.</span></span>
                        <span class="text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center">@version('version-only') @version('prerelease')</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>
</html>
