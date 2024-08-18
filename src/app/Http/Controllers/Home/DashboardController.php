<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home/Dashboard', [
            'bookmarks' => [
                'techTips' => $request->user()->TechTipBookmarks,
                'customers' => $request->user()->CustomerBookmarks,
            ],
            'recent' => [
                'techTips' => $request->user()->TechTipRecent,
                'customers' => $request->user()->CustomerRecent,
            ],
        ]);
    }
}
