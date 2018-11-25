<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\FileLinks;

class AdminController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page
    public function index()
    {
        return view('admin.index');
    }
    
    //  File Link Administration page
    public function links()
    {
        $userLinks = User::with('FileLinks')->get();

        return view('admin.links', [
            'links' => $userLinks
        ]);
    }
    
    //  Show the links for a specific user
    public function showLinks($userID)
    {
        $user = User::find($userID);
        $userName = $user->first_name.' '.$user->last_name;
        
        
        return view('admin.fileLinks', [
            'userID' => $userID,
            'name' => $userName
        ]);
    }
}
