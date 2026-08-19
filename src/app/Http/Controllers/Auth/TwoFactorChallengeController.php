<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\Request;

class TwoFactorChallengeController extends Controller
{
    public function __construct(protected TwoFactorService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        dd('two fa challenge');

    }
}
