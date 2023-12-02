<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
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

    protected $overrideRoutes = [
        'bypass-test',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if ($response->status() === 404
            && in_array(Route::current()
            && Route::current()->getName(), $this->overrideRoutes)
        ) {
            return $response;
        }

        if (! app()->environment([/**'local', */ 'testing'])
            && in_array($response->status(), [500, 503, 404, 403, 429])
        ) {
            switch ($response->status()) {
                case 500:
                case 503:
                    $level = 'critical';
                    break;
                case 404:
                case 429:
                    $level = 'warning';
                    break;
                case 403:
                    $level = 'alert';
                    break;
                default:
                    $level = 'notice';
            }

            Log::$level('Server Error - '.$response->status().' occured.', [
                'message' => $response->exception->getMessage(),
                'url' => $request->fullUrl(),
                'auth' => Auth::check() ? true : false,
                'user_id' => Auth::check() ? Auth::user()->user_id : null,
                'username' => Auth::check() ? Auth::user()->username : null,
                'method' => $request->getMethod(),
                'ip_address' => $request->ip(),
            ]);

            return Inertia::render('Error', [
                'status' => $response->status(),
            ])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->withErrors('The page expired.  Please Refresh and try again');
        }

        return $response;
    }
}
