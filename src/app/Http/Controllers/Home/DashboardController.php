<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Home/Dashboard', [
            'bookmarks' => [
                'techTips' => [], // $request->user()->TechTipBookmarks,
                'customers' => [], // $request->user()->CustomerBookmarks,
            ],
            'recent' => [
                'techTips' => [], // $request->user()->TechTipRecent,
                'customers' => [], // $request->user()->CustomerRecent,
            ],
        ]);
    }
}
