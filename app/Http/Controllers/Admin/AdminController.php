<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class AdminController extends Controller
{
    public function __construct()
    {
        //  Only Authorized users with specific admin permissions are allowed
        $this->middleware(['auth', 'can:allow_admin']);
    }
    
    //  Admin landing page
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.index');
    }
    
    //  Display all file links
    public function userLinks()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userLinks');
    }
    
    //  Get the number of links for each user
    public function countLinks()
    {
        $userLinks = User::with('FileLinks')->get();
        $linkCount = [];
        
        foreach($userLinks as $user)
        {
            $expired = $user->FileLinks->filter(function($lnk) {
                           if($lnk->expire < date('Y-m-d'))
                           {
                               return $lnk;
                           }
                       })->count();

            $linkCount[] = [
                'user_id' => $user->user_id,
                'name'    => $user->first_name.' '.$user->last_name,
                'total'   => $user->FileLinks->count(),
                'expired' => $expired
            ];
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Link Count', $linkCount);
        return response()->json($linkCount);
    }
    
    //  Show the links for the selected user
    public function showLinks($id)
    {
        $user     = User::find($id);
        $userName = $user->first_name.' '.$user->last_name;
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.linkDetails', [
            'userID' => $id,
            'name'   => $userName
        ]);
    }
}
