<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
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

        if(!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403]))
        {
            Log::notice('Server Error - '.$response->status().' occured.  Message - '.$response->exception->getMessage(), [
                'auth'     => Auth::check() ? true : false,
                'user_id'  => Auth::check() ? Auth::user()->user_id : null,
                'username' => Auth::check() ? Auth::user()->username : null,
            ]);

            $message = $response->exception->getMessage();

            if($message == "" || Str::startsWith($message, 'No query results'))
            {
                $message = null;
            }

            return Inertia::render('Error', [
                    'status'  => $response->status(),
                    'message' => $message,
                ])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }
        else if ($response->status() === 419)
        {
            return back()->with([
                'message' => 'The page expired, please try again.',
                'type'    => 'danger',
            ]);
        }

        return $response;
    }
}
