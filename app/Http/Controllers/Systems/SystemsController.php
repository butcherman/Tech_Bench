<?php

namespace App\Http\Controllers\Systems;

use App\SystemTypes;
use App\SystemFileTypes;
use App\SystemCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SystemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Allow the user to select a category to view
    public function index()
    {
        $categories = SystemCategories::all();
        
        return view('system.index', [
            'categories' => $categories
        ]);
    }
    
    //  Select the system type for the given category
    public function selectSys($cat)
    {
        //  Make sure that the category is valid
        $valid = SystemCategories::where('name', $cat)->get();
        if($valid->isEmpty())
        {
            Log::warning('User '.Auth::user()->user_id.' tried to visit invalid category '.$cat);
            return view('err.badCategory');
        }
        
        //  Get the types of systems belonging to the category
        $sysList = SystemTypes::whereHas('SystemCategories', function($q) use($cat) {
            $q->where('name', $cat);
        })->get();
        
        return view('system.selectSystem', [
            'systems'  => $sysList,
            'category' => $cat
        ]);
    }
    
    //  Show the details of the selected system
    public function details($cat, $sys)
    {
        //  Make sure that the system is valid
        $sys = urldecode($sys);
        $valid = SystemTypes::where('name', $sys)->first();
        if(empty($valid))
        {
            Log::warning('User '.Auth::user()->user_id.' tried to visit invalid system - '.$sys.' for category '.$cat);
            return view('err.badSystem');
        }

        //  Get the latest Tech Tips for this system
//        $tipData = TechTips::whereHas('TechTipSystems', function($q) use($valid)
//        {
//            $q->where('sys_id', $valid->sys_id);
//        })->orderBy('created_at', 'DESC')->get();
        
        return view('system.details', [
            'sysName'   => $sys,
            'sysID' => $valid->sys_id,
            'category'  => $cat
        ]);
    }
}
