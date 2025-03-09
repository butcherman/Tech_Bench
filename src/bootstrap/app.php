<?php

use App\Actions\Misc\BuildNavBar;
use App\Http\Middleware\CheckForInit;
use App\Http\Middleware\CheckForTwoFactor;
use App\Http\Middleware\CheckPasswordExpiration;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\InitializeApp;
use App\Http\Middleware\LogDebugVisits;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\EncryptHistoryMiddleware;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

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
        ])->appendToGroup('auth.secure', [
            Authenticate::class,
            CheckForInit::class,
            CheckForTwoFactor::class,
            CheckPasswordExpiration::class,
            EncryptHistoryMiddleware::class,
        ])->alias([
            'init' => InitializeApp::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            // $middlewareData = (new HandleInertiaRequests(new BuildNavBar))->share($request);

            // Template to be chosen is based on if a user is logged in or not
            $errPage = $request->user() ? 'ErrorAuth' : 'ErrorGuest';
            $catchable = [500, 503, 404, 403, 429];
            $statusCode = $response->getStatusCode();

            // If we are not in production, 500 type errors should show symphony error page
            if (! app()->environment('production') && $statusCode === 500) {
                return $response;
            }

            // // Handle catchable errors with an Inertia Page
            if (in_array($response->getStatusCode(), $catchable)) {
                return Inertia::render($errPage, array_merge([], [
                    'status' => $statusCode,
                    'message' => $statusCode !== 500 ? $exception->getMessage() : null,
                ]))
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } elseif ($response->getStatusCode() === 419) {
                return back()->withErrors([
                    'message' => 'The page expired, please try again.',
                ]);
            }

            return $response;
        });
    })->create();
