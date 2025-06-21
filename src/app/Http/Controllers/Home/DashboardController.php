<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the application Dashboard
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Home/Dashboard', [
            'bookmarks' => Inertia::defer(fn () => [
                'techTips' => $request->user()->TechTipBookmarks,
                'customers' => $request->user()->CustomerBookmarks,
            ]),
            'recent' => Inertia::defer(fn () => [
                'techTips' => $request->user()->RecentTechTips,
                'customers' => $request->user()->RecentCustomers,
            ]),
        ]);
    }
}
