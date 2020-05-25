<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Domains\User\GetUserStats;
use App\Domains\User\UserNotifications;
use App\Domains\TechTips\GetTechTipStats;

class DashboardController extends Controller
{
    //  Home page for authorized users
    public function index()
    {
        $userObj = new GetUserStats(Auth::user()->user_id);
        $tipsObj = new GetTechTipStats;

        return view('dashboard', [
            'custFavs'    => $userObj->getUserCustomerFavs(),
            'tipFavs'     => $userObj->getUserTipFavs(),
            'tips30'      => $tipsObj->getTipsInLastDays(),
            'tipsAll'     => $tipsObj->getTotalTipsCount(),
            'linksActive' => $userObj->getUserActiveLinks(),
            'linksTotal'  => $userObj->getUserTotalLinks(),
        ]);
    }

    //  Retrieve all users notifications
    public function getNotifications()
    {
        return Auth::user()->notifications;
    }

    //  Mark a notification as read
    public function markNotification($id)
    {
        (new UserNotifications)->markNotificationRead($id);

        return response()->json(['success' => true]);
    }

    //  Delete a notification
    public function delNotification($id)
    {
        (new UserNotifications)->deleteNotification($id);

        return response()->json(['success' => true]);
    }
}
