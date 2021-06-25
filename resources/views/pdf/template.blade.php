<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name', 'Tech Bench')}}</title>
    <link rel="stylesheet" href="{{base_path('resources/css/pdf.css')}}">

</head>
<body>
    <div style="border-bottom: 1px solid #585555" class="mb-3">
        <div class="row">
            <div class="col-12">
                <img src="{{public_path(config('app.logo'))}}" alt="Company Logo" id="header-logo" style="height: 3rem" class="float-left d-block mx-3" />
                <h1 class="text-center">
                    {{config('app.name', 'Tech Bench')}}
                    @hasSection('title')
                        -
                        @yield('title')
                    @endif
                </h1>
            </div>
        </div>
    </div>
    <div>
        @yield('content')
    </div>
</body>
</html>
