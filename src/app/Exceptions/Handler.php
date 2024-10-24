<?php

namespace App\Exceptions;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Handle Exceptions with Inertia Pages
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function render($request, Throwable $e): Response
    {
        $middlewareData = (new HandleInertiaRequests)->share($request);
        $response = parent::render($request, $e);
        $catchable = [500, 503, 404, 403];
        $statusCode = $response->getStatusCode();

        // Template to be chosen is based on if a user is logged in or not
        $errPage = $request->user() ? 'ErrorAuth' : 'ErrorGuest';

        // If we are not in production mode, 500 errors should return symphony error page
        if (! App::environment('production') && $statusCode === 500) {
            return $response;
        }

        // Handle all catchable errors with an Inertia request
        if (in_array($statusCode, $catchable)) {
            return Inertia::render($errPage, array_merge($middlewareData, [
                'status' => $statusCode,
                'message' => $e->getMessage(),
            ]))
                ->toResponse($request)->setStatusCode($statusCode);
        }

        // Error 419 goes back with note to refresh page
        if ($statusCode === 419) {
            // @codeCoverageIgnoreStart
            return back()->withErrors('Page Expired, Please Refresh and Try Again');
            // @codeCoverageIgnoreEnd
        }

        // All other uncaught codes will return symphony error page
        return $response;
    }
}
