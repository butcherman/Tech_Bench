<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\UserCustomerBookmark;
use App\Models\UserCustomerRecent;
use App\Models\UserTechTipBookmark;
use App\Models\UserTechTipRecent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nwidart\Modules\Facades\Module;

class DashboardController extends Controller
{
    /**
     *  Landing page for authorized users
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home/Dashboard', [
            'bookmarks' => [
                'customers' => UserCustomerBookmark::where('user_id', $request->user()->user_id)->get(),
                'tips' => UserTechTipBookmark::where('user_id', $request->user()->user_id)->get(),
            ],
            'recent' => [
                'customers' => UserCustomerRecent::where('user_id', $request->user()->user_id)->orderBy('updated_at', 'DESC')->get()->take(10),
                'tips' => UserTechTipRecent::where('user_id', $request->user()->user_id)->orderBy('updated_at', 'DESC')->get()->take(10),
            ],
        ]);
    }
}
