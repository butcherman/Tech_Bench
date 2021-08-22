<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        @routes
        <script src="{{ mix('/js/app.js') }}" defer></script>
    </head>
    <body>
        <noscript>
            <h4 class="text-center">Javascript is Disabled</h4>
            <p class="text-center">
                {{config('app.name')}} requires Javascript to work properly.
            </p>
            <p class="text-center">
                Please enable Javascript and reload page
            </p>
        </noscript>
        @inertia
        @include('cookieConsent::index')
    </body>
</html>
