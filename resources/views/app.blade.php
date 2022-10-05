<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
{{-- @foreach (Module::allEnabled() as $module)
    @if(file_exists(public_path('css/'.strtolower($module->getName()).'.css')))
        <link href="{{ '/css/'.strtolower($module->getName()).'.css' }}" rel="stylesheet" />
    @endif
    @if(file_exists(public_path('js/'.strtolower($module->getName()).'.js')))
        <script src="{{ '/js/'.strtolower($module->getName()).'.js' }}" defer></script>
    @endif
@endforeach --}}
        @routes
        @vite('resources/js/app.js')
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
