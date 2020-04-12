<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Domains\Users\GetUserStats;
use App\Domains\TechTips\GetTechTipStats;
use App\Domains\Users\MarkUserNotifications;

class DashboardController extends Controller
{
    public function __construct()
    {
        //  Only authorized users have access to these pages
        $this->middleware('auth');
    }

    //  Dashboard is the Logged In User home landing page
    public function index()
    {
        $userStats = new GetUserStats;
        $tipStats  = new GetTechTipStats;

        return view('dashboard', [
            'custFavs'    => $userStats->getUserCustomerFavs(),
            'tipFavs'     => $userStats->getUserTechTipFavs(),
            'tips30'      => $tipStats->getTipsInLastDays(30),
            'tipsAll'     => $tipStats->getTotalTechTipCount(),
            'activeLinks' => $userStats->getUserActiveFileLinks(),
            'totalLinks'  => $userStats->getUserTotalLinks(),
        ]);
    }

    //  Get the users notifications
    public function getNotifications()
    {
        return Auth::user()->notifications;
    }

    //  Mark a notification as read
    public function markNotification($id)
    {
        (new MarkUserNotifications)->markNotificationRead($id);

        return response()->json(['success' => true]);
    }

    //  Delte a user notification
    public function delNotification($id)
    {
        (new MarkUserNotifications)->deleteNotification($id);

        return response()->json(['success' => true]);
    }

    //  About page
    public function about()
    {
        return view('about', ['branch' => 'latest']);
    }
}
