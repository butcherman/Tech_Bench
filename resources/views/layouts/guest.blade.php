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
    <script>var maxUpload = {{env('MAX_UPLOAD')}}</script>
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    
</head>
<body role="document">
    <div class="container-fluid" role="main">
        @yield('content')
    </div>
    @yield('script')
</body>
</html>
