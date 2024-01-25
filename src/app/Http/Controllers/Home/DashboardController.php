<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Karmendra\LaravelAgentDetector\AgentDetector;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $userAgent = $_SERVER['HTTP_USER_AGENT'];

        // $ad = new AgentDetector($userAgent);

        // dd($ad);

        // return $ad;

        return Inertia::render('Home/Dashboard', [
            // 'agent' => $userAgent,
            // 'ad' => $ad,
            // 'platform' => $ad->platformVersion(),
            // 'browser' => $ad->browserVersion(),
        ]);
    }
}
