<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemCategories;
use App\SystemTypes;
use App\SystemFileTypes;
use App\SystemCustDataFields;

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
        $sysList = SystemTypes::whereHas('SystemCategories', function($q) use($cat)
        {
            $q->where('name', $cat);
        })->get();
        
        return view('system.selectSystem', [
            'systems' => $sysList,
            'category' => $cat
        ]);
    }
    
    //  Details page that shows the system information
    public function details($cat, $sys)
    {
        $fileTypes = SystemFileTypes::all();
        
        return view('system.details', [
            'sysName' => $sys,
            'fileTypes' => $fileTypes,
            'category' => $cat
        ]);  
    }
    
    //  Get the fields of information to gather for customer that has this system
    public function fields($sysID)
    {
        $sysFields = SystemCustDataFields::where('sys_id', $sysID)
            ->join('System_Cust_Data_Types', 'System_Cust_Data_Types.data_type_id', '=', 'System_Cust_Data_Fields.data_type_id')
            ->orderBy('order', 'ASC')
            ->get();
        
        return view('customer.form.systemFields', [
            'sysFields' => $sysFields
        ]);
    }
}
