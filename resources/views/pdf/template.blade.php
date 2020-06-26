<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name', 'Tech Bench')}}</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>
    <div class="m-5">
        <div class="row justify-content-center pb-2 mb-5 border-bottom">
            <div class="col-12">
                <h1 class="text-center">
                {{-- <img src="{{config('app.logo')}}" alt="Company Logo" id="header-logo" style="height: 75px" class="float-left d-block ml-5" /> --}}
                {{config('app.name', 'Tech Bench')}}</h1>
            </div>
        </div>
        @yield('content')
    </div>
</body>
</html>
