<?php

namespace App\Http\Controllers;

use Module;
use App\TechTipFavs;
use App\CustomerFavs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Zip;
use Carbon\Carbon;

//use Nwidart\Modules;
use Mail;
use App\Mail\InitializeUser;

use App\TechTips;
use App\FileLinks;
// use Carbon\Carbon;

use App\User;
use App\UserRolePermissions;
use App\UserRoles;
use App\UserRolePermissionTypes;
// use App\TechTips;
use App\SystemTypes;


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
//            'modules'       => $modules
        ]);
    }

    //  About page
    public function about()
    {
        exec('git symbolic-ref HEAD', $output);
        $t = explode('/', $output[0]);
        $branch = end($t) === 'master' ? 'latest' : end($t);

        return view('about', [
            'branch' => $branch
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
            return abort(404);
            Log::notice('Notification ID-'.$id.' not found for user ID-'.Auth::user()->user_id);
        }

        return response()->json(['success' => true]);
    }
}
