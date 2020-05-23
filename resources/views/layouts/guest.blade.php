<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tech Bench') }}</title>
    <!-- Scripts -->
    <script>
        window.techBench = {
            'maxUpload': '{{config('filesystems.paths.max_size')}}',
            'csrfToken': '{{csrf_token()}}'
        };
    </script>
    <script src="{{ mix('js/guest.js') }}"></script>

    <!-- Styles -->
    <link href="{{ mix('css/guest.css') }}" rel="stylesheet">
</head>
<body role="document">
    <div class="container-fluid v-100" role="main" id="app">
        @yield('content')
    </div>
</body>
</html>
