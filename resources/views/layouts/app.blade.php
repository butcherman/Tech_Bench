<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{config('app.name', 'Tech Bench')}}</title>



    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <script>
        window.techBench = {
            'maxUpload': '{{config('filesystems.paths.max_size')}}',
            'csrfToken': '{{csrf_token()}}'
        };
    </script>
    <script src="{{mix('js/app.js')}}"></script>
</head>
<body>
    <div class="container-scroller" id="app">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">

            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <h2>{{config('app.name', 'Tech Bench')}}</h2>
                    </li>
                </ul>
                <u
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" onclick="expandNav()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">

            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <footer class="footer">
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
