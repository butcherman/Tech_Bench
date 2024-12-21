<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerListResource;
use App\Http\Resources\TechTip\TechTipListResource;
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
                'techTips' => TechTipListResource::collection(
                    $request->user()->TechTipBookmarks
                ),
                'customers' => CustomerListResource::collection(
                    $request->user()->CustomerBookmarks
                ),
            ],
            'recent' => fn() => [
                'techTips' => TechTipListResource::collection(
                    $request->user()->RecentTechTips
                ),
                'customers' => CustomerListResource::collection(
                    $request->user()->RecentCustomers
                ),
            ],
        ]);
    }
}
