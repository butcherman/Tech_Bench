<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\SystemCategories;
use App\SystemTypes;
use App\SystemFileTypes;
use App\SystemCustDataFields;
use App\TechTips;

class SystemController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page if no category was selected
    public function index()
    {
        $categories = SystemCategories::all();
        
        return view('system.index', [
            'categories' => $categories
        ]);
    }
    
    //  Page to select the system type for the given category
    public function selectSystem($cat)
    {
        //  Make sure that the category is valid
        $valid = SystemCategories::where('name', $cat)->get();
        if($valid->isEmpty())
        {
            Log::warning('User '.Auth::user()->user_id.' tried to visit invalid category '.$cat);
            return view('errors.badCategory');
        }
        
        //  Get the types of systems belonging to the category
        $sysList = SystemTypes::whereHas('SystemCategories', function($q) use($cat)
        {
            $q->where('name', $cat);
        })->get();
        
        return view('system.selectSystem', [
            'systems'  => $sysList,
            'category' => $cat
        ]);
    }
    
    //  Details page that shows the system information
    public function details($cat, $sys)
    {
        //  Make sure that the system is valid
        $sys = urldecode($sys);
        $valid = SystemTypes::where('name', $sys)->first();
        if(empty($valid))
        {
            Log::warning('User '.Auth::user()->user_id.' tried to visit invalid system - '.$sys.' for category '.$cat);
            return view('errors.badSystem');
        }
        
        //  Get the file types and build the basic page before Ajax calls
        $fileTypes = SystemFileTypes::all();

        //  Get the latest Tech Tips for this system
        $tipData = TechTips::whereHas('TechTipSystems', function($q) use($valid)
        {
            $q->where('sys_id', $valid->sys_id);
        })->orderBy('created_at', 'DESC')->get();
        
        return view('system.details', [
            'sysName'   => $sys,
            'fileTypes' => $fileTypes,
            'category'  => $cat,
            'results'   => $tipData
        ]);  
    }
    
    //  Get the fields of information to gather for customer that has this system
    public function fields($sysID)
    {
        $sysFields = SystemCustDataFields::where('sys_id', $sysID)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->orderBy('order', 'ASC')
            ->get();
        
        return view('customer.form.systemFields', [
            'sysFields' => $sysFields
        ]);
    }
}
