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


        // $path = config('filesystems.disks.public.root') . DIRECTORY_SEPARATOR;

        // $zip = Zip::create($path.'test_zip_'.Carbon::now()->timestamp.'.zip');
        // $zip->add('C:\Users\RonButcher\Documents\GitHub\Tech_Bench\storage\app\files\99\31576-11-0--SV9100-FeatSpecs.pdf');
        // $zip->close();

        // dd($zip);





        //  Get the users notifications
//        $notifications = Auth::user()->unreadNotifications;

        //  Get the Add-On Modules to list routes for
//        $modules = Module::all();

        //  Get the users Customer bookmarks
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
//            'notifications' => $notifications->toArray(),
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
