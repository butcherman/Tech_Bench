<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name', 'Tech Bench') }}</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    >
</head>

<body class="container-fluid my-4">
    <div
        style="border-bottom: 1px solid #585555"
        class="mb-3 pb-3"
    >
        <div class="row">
            <div class="col-12">
                <img
                    src="{{ public_path(config('app.logo')) }}"
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
        this is content
    </div>
</body>

</html>
