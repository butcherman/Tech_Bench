<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Admin landing page
    public function index()
    {
        return view('admin.index');
    }
    
    //  Display all file links
    public function userLinks()
    {
        return view('admin.userLinks');
    }
    
    //  Get the number of links for each user
    public function countLinks()
    {
        $userLinks = User::with('FileLinks')->get();
        $linkCount = [];
        
        foreach($userLinks as $user)
        {
            $expired = $user->FileLinks->filter(function($lnk)
                       {
                           if($lnk->expire < date('Y-m-d'))
                           {
                               return $lnk;
                           }
                       })->count();

            
            $linkCount[] = [
                'name'    => $user->first_name.' '.$user->last_name,
                'total'   => $user->FileLinks->count(),
                'expired' => $expired
            ];
        }
        
        return response()->json($linkCount);
    }
}
