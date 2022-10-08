<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application598
     *
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });
    }

    /**
     * Check for an error message
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if(!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403, 429]))
        {
            switch($response->status())
            {
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
                'message'    => $response->exception->getMessage(),
                'url'        => $request->fullUrl(),
                'auth'       => Auth::check() ? true : false,
                'user_id'    => Auth::check() ? Auth::user()->user_id : null,
                'username'   => Auth::check() ? Auth::user()->username : null,
                'method'     => $request->getMethod(),
                'ip_address' => $request->ip(),
            ]);

            return Inertia::render('Error', [
                    'status'  => $response->status(),
                ])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }
        else if ($response->status() === 419)
        {
            return back()->withErrors('The page expired.  Please Refresh and try again');
        }

        return $response;
    }
}
