<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>{{config('app.name', 'Tech Bench')}}</title>
    <script>
        window.techBench = {
            'maxUpload': '{{config('filesystems.paths.max_size')}}',
            'csrfToken': '{{csrf_token()}}'
        };
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <navbar 
            brand="{{config('app.name', 'Tech Bench')}}"
            dashboard_route="{{route('index')}}"
            logout_route="{{route('logout')}}"
            help_route="{{route('about')}}"
            links_route="{{route('links.index')}}"
            account_route="{{route('account')}}"
            cust_route="{{route('customer.index')}}"
            tech_tip_route="{{route('tip.id.index')}}"
        @if(Auth::user()->hasAnyRole(['installer', 'admin']))    
            admin_route="{{route('admin.index')}}"  
        @endif
            sys_list="{{json_encode($navArray)}}"
        ></navbar>
        <div class="content-wrapper">
            <div class="container-fluid">
                @yield('breadcrumbs')
                @yield('content')
            </div>
        </div>
        <footer class="sticky-footer">
            <div class="container">
                <div class="text-center">
                    <small>Copyright &copy; Tech Bench 2016-2019 - Version - @version('version')</small>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
