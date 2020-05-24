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
        <nav class="navbar top-navbar">
            <div class="navbar-logo-wrapper d-flex">
                <a class="navbar-logo mr-5" href="{{route('dashboard')}}"><img src="{{config('app.logo')}}" class="mr-2" alt="Tech Bench"/></a>
            </div>
            <div class="navbar-brand d-none d-md-flex">
                <h2>{{config('app.name', 'Tech Bench')}}</h2>
            </div>
            <div class="navbar-data">
                <b-button href="#" variant="light" size="sm" pill title="Help" v-b-tooltip.hover>
                    <i class="far fa-question-circle"></i>
                </b-button>
                <b-button href="#" variant="light" size="sm" pill title="About {{config('app.name')}}" v-b-tooltip.hover>
                    <i class="fas fa-info-circle"></i>
                </b-button>
                <b-form method="POST" action="/logout" class="d-inline">
                    @csrf
                    <b-button type="submit" pill size="sm" title="Log Off" v-b-tooltip.hover variant="light">
                        <i class="fas fa-sign-out-alt"></i>
                    </b-button>
                </b-form>
                <a href="#" title="Settings" v-b-tooltip.hover>
                    <b-avatar variant="warning" text="{{Auth::user()->initials}}"></b-avatar>
                </a>
            </div>
        </nav>
    </div>
</body>
</html>
