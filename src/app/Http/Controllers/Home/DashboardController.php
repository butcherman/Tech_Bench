<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the Dashboard Page.
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Home/Dashboard', [
            'bookmarks' => fn() => [
                'techTips' => $request->user()->TechTipBookmarks,
                'customers' => $request->user()->CustomerBookmarks,
            ],
            'recent' => fn() => [
                'techTips' => $request->user()->RecentTechTips,
                'customers' => $request->user()->RecentCustomers,
            ],
        ]);
    }
}
