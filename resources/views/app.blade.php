<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        @routes
        @vite('resources/js/app.ts')
        @inertiaHead
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
    </body>
</html>
