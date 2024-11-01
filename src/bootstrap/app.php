<?php

use App\Http\Middleware\CheckForTwoFactor;
use App\Http\Middleware\CheckPasswordExpiration;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\LogDebugVisits;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/administration/up',   // TODO - make this work
        using: function () {
            Route::middleware('web')
                ->group(
                    function () {
                        $routes = glob(base_path('routes/web/*.php'));
                        foreach ($routes as $route) {
                            require $route;
                        }
                    }
                );
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            LogDebugVisits::class,
            HandleInertiaRequests::class,
        ])
            ->appendToGroup('auth.secure', [
                Authenticate::class,
                CheckForTwoFactor::class,
                CheckPasswordExpiration::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
