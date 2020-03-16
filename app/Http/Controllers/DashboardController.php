<?php

namespace App\Http\Controllers;

use Module;
use App\TechTips;
use App\FileLinks;
use Carbon\Carbon;
use App\TechTipFavs;
use App\CustomerFavs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



use Illuminate\Http\Request;

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
        $custFavs    = CustomerFavs::where('user_id', Auth::user()->user_id)->with('Customers')->get();
        $tipFavs     = TechTipFavs::where('user_id', Auth::user()->user_id)->with('TechTips')->get();
        $tips30Days  = TechTips::where('created_at', '>', Carbon::now()->subDays(30))->count();
        $tipsTotal   = TechTips::all()->count();
        $activeLinks = FileLinks::where('user_id', Auth::user()->user_id)->where('expire', '>', Carbon::now())->count();
        $totalLinks  = FileLinks::where('user_id', Auth::user()->user_id)->count();

        return view('dashboard', [
           'custFavs'    => $custFavs,
           'tipFavs'     => $tipFavs,
           'tips30'      => $tips30Days,
           'tipsAll'     => $tipsTotal,
           'activeLinks' => $activeLinks,
           'totalLinks'  => $totalLinks,
        ]);
    }

    //  About page
    public function about()
    {
        return view('about', [
            'branch' => 'latest'
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
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        if(!$notification)
        {
            return abort(404);
        }
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    //  Deelte a user notification
    public function delNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        if($notification)
        {
            $notification->delete();
            Log::info('Notification ID-'.$id.' deleted for User ID-'.Auth::user()->user_id);
        }
        else
        {
            Log::notice('Notification ID-'.$id.' not found for user ID-'.Auth::user()->user_id);
            return abort(404);
        }

        return response()->json(['success' => true]);
    }
}
