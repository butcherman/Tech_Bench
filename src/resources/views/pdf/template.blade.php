<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Tech Bench') }}</title>
    @vite('resources/scss/app.scss')
</head>

<body class="container-fluid my-4">
    <div
        style="border-bottom: 1px solid #585555"
        class="my-3 pb-3"
    >
        <div class="row">
            <div class="col-12">
                {{-- FIXME - Logo Does Not Load --}}
                <img
                    src="{{ config('app.logo') }}"
                    alt="Company Logo"
                    id="header-logo"
                    style="height: 3rem"
                    class="float-start d-block mx-3"
                />
                <h1 class="text-center mb-2">
                    {{ config('app.name', 'Tech Bench') }}
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
