<?php

namespace App\Http\Controllers;

use Module;
use App\TechTipFavs;
use App\CustomerFavs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



//use Nwidart\Modules;
use Mail;
use App\Mail\InitializeUser;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Dashboard is the Logged In User home landing page
    public function index()
    {
        //  Get the users notifications
        $notifications = Auth::user()->unreadNotifications;
        
        //  Get the Add-On Modules to list routes for
        $modules = Module::all();
//        
//        //  Get the users Customer bookmarks
//        $custFavs = CustomerFavs::where('user_id', Auth::user()->user_id)
//            ->LeftJoin('customers', 'customer_favs.cust_id', '=', 'customers.cust_id')
//            ->get();
//        //  Get the users Tech Tip bookmarks
//        $tipFavs = TechTipFavs::where('tech_tip_favs.user_id', Auth::user()->user_id)
//            ->LeftJoin('tech_tips', 'tech_tips.tip_id', '=', 'tech_tip_favs.tip_id')
//            ->get();
        
        return view('dashboard', [
//            'custFavs' => $custFavs,
//            'tipFavs'  => $tipFavs,
            'notifications' => $notifications->toArray(),
            'modules'       => $modules
        ]);
    }
    
    //  About page
    public function about()
    {
        return response('About Page');
    }
    
    //  Get the users notifications
    public function getNotifications()
    {
        return Auth::user()->unreadNotifications->toJson();
    }
    
    public function delNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if($notification)
        {
            $notification->delete();
            Log::info('Notification ID-'.$id.' deleted for User ID-'.Auth::user()->user_id);
        }
        else
        {
            Log::notice('Notification ID-'.$id.' not found for user ID-'.Auth::user()->user_id);
        }
        
        return response()->json(['success' => true]);
    }
}
