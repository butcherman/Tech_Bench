<?php

namespace App\Http\Controllers;

use App\TechTips;
use App\FileLinks;
use Carbon\Carbon;
use App\TechTipFavs;
use App\CustomerFavs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

        //  Debug Data
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);
        Log::debug('Customer Favorites for '.Auth::user()->full_name.': ', $custFavs->toArray());
        Log::debug('Tech Tip Favorites for ' . Auth::user()->full_name . ': ', $tipFavs->toArray());

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
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        return view('about', [
            'branch' => 'latest'
        ]);
    }

    //  Get the users notifications
    public function getNotifications()
    {
        //  Debug Data
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);
        Log::debug('Notifications for '.Auth::user()->full_name.':', Auth::user()->notifications->toArray());

        return Auth::user()->notifications;
    }

    //  Mark a notification as read
    public function markNotification($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        if(!$notification)
        {
            Log::error('User '.Auth::user()->full_name.' tried to mark an invalid notification as read.  Notification ID: '.$id);
            return abort(404);
        }
        $notification->markAsRead();

        Log::debug('User '.Auth::user()->full_name.' marked notification ID '.$id.' as read');

        return response()->json(['success' => true]);
    }

    //  Delte a user notification
    public function delNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        if($notification)
        {
            $notification->delete();
            Log::info('Notification ID-'.$id.' deleted for '.Auth::user()->full_name);
        }
        else
        {
            Log::error('User ' . Auth::user()->full_name . ' tried to delete an invalid notification as read.  Notification ID: ' . $id);
            return abort(404);
        }

        return response()->json(['success' => true]);
    }
}
