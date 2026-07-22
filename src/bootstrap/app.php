<?php

use App\Http\Middleware\CheckForInit;
use App\Http\Middleware\CheckForTwoFactor;
use App\Http\Middleware\CheckPasswordExpiration;
use App\Http\Middleware\HandleFlashDataMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\InitializeApp;
use App\Http\Middleware\LogDebugVisits;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\EncryptHistoryMiddleware;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
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
            Route::get('/healthcheck', function () {
                DB::select('SELECT 1');

                return response()->json(['status' => 'ok']);
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            LogDebugVisits::class,
            HandleInertiaRequests::class,
            HandleFlashDataMiddleware::class,
        ])->appendToGroup('auth.secure', [
            Authenticate::class,
            CheckForInit::class,
            // CheckForTwoFactor::class,
            CheckPasswordExpiration::class,
            EncryptHistoryMiddleware::class,
        ])->alias([
            'init' => InitializeApp::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // TODO - Determine which Layout to use based on the page user came from
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            // Handle 500, 503, 404, & 403 errors with Inertia page
            if (! app()->environment(['local', 'testing']) && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
                return Inertia::render('Error', ['status' => $response->getStatusCode()])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            }

            return $response;
        });
    })->create();
