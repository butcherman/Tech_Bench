<?php

namespace App\Http\Controllers\Home;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\UserCustomerBookmark;
use App\Models\UserTechTipBookmark;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     *  Landing page for authorized users
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home/Dashboard', [
            'notifications' => $request->user()->notifications,
            'bookmarks'     => [
                'customers' => UserCustomerBookmark::where('user_id', $request->user()->user_id)->get(),
                'tips'      => UserTechTipBookmark::where('user_id', $request->user()->user_id)->get(),
            ]
        ]);
    }
}
