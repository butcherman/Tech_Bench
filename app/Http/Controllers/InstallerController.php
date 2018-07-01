<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemCustDataFields;
use App\SystemCustDataTypes;
use App\SystemCategories;
use App\SystemTypes;

class InstallerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        //  Get the list of system categories
        $cats = SystemCategories::all();
        $sysArr = [];
        //  Populate that list with the matching systems
        foreach($cats as $cat)
        {
            $systems = SystemTypes::where('cat_id', $cat->cat_id)->get();
            if(!$systems->isEmpty())
            {
                foreach($systems as $sys)
                {
                    $sysArr[$cat->name][] = $sys->name;
                }
            }
            else
            {
                $sysArr[$cat->name] = null;
            }
        }
        
        return view('installer.index', [
            'sysArr' => $sysArr
        ]); 
    }
    
    public function newCat()
    {
        return view('installer.form.newCat');
    }
    
    public function submitCat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:system_categories|regex:/^[a-zA-Z0-9_ ]*$/'
        ]);
        
        $cat = SystemCategories::create([
            'name' => $request->name
        ]);
        
        return redirect()->back()->with('success', 'Category Successfully Added. <a href="'.route('installer.newSys', urlencode($cat->name)).'">Add System</a>');
    }
                                      
    public function newSystem($cat)
    {
        $dropDown = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        
        return view('installer.form.newSystem', [
            'cat'      => $cat,
            'dropDown' => $dropDown
        ]);
    }
    
    public function submitSys($cat, Request $request)
    {
        $catName = SystemCategories::where('name', urldecode($cat))->first()->cat_id;
        $sysData = SystemTypes::create([
            'cat_id' => $catName,
            'name' => $request->name,
            'parent_id' => null,
            'folder_location' => str_replace(' ', '_', $request->name)
        ]);
        $sysID = $sysData->sys_id;
        $i = 0;
        
        foreach($request->custField as $field)
        {
            if(!empty($field))
            {
                $id = SystemCustDataTypes::where('name', $field)->first();

                if(is_null($id))
                {
                    $newField = SystemCustDataTypes::create([
                        'name' => $field
                    ]);
                    $id = $newField->data_type_id;
                }
                else
                {
                    $id = $id->data_type_id;
                }

                SystemCustDataFields::create([
                    'sys_id' => $sysID,
                    'data_type_id' => $id,
                    'order' => $i
                ]);

                $i++;
            }
        }
        
        return redirect()->back()->with('success', 'System Successfully Added');//
    }
    
    public function editSystem($sysName)
    {        
        $sysData = SystemTypes::where('name', urldecode($sysName))->first();
        $dropDown = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        $dataType = SystemCustDataFields::where('sys_id', $sysData->sys_id)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->get();
        
        return view('installer.form.editSystem', [
            'dropDown' => $dropDown,
            'fields'   => $dataType,
            'name'     => $sysData->name,
            'sysID'    => $sysData->sys_id
        ]);
    }
    
    public function submitEditSystem($sysID, Request $request)
    {
        $sysName = SystemTypes::find($sysID)->name;
        
        //  Change the name of the system if it has been modified
        if($sysName !== $request->name)
        {
            SystemTypes::find($sysID)->update([
                'name' => $request->name
            ]);
        }
        
        //  Update the Customer Information
        $dataType = SystemCustDataFields::where('sys_id', $sysID)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->get();
        
        $i = 0;
        foreach($request->custField as $field)
        {
            $found = false;
            if(!empty($field))
            {
                //  Check if the field already exists
                foreach($dataType as $type)
                {
                    if($type->name === $field)
                    {
                        $found = true;
                        //  See if the order has changed
                        if($type->order != $i)
                        {
                            SystemCustDataFileds::find($type->field_id)->update('order', $i);
                        }
                    }
                }
                //  If the field does not exist, create it
                if(!$found)
                {
                    $id = SystemCustDataTypes::where('name', $field)->first();

                    if(is_null($id))
                    {
                        $newField = SystemCustDataTypes::create([
                            'name' => $field
                        ]);
                        $id = $newField->data_type_id;
                    }
                    else
                    {
                        $id = $id->data_type_id;
                    }

                    SystemCustDataFields::create([
                        'sys_id' => $sysID,
                        'data_type_id' => $id,
                        'order' => $i
                    ]);
                }
            }
            
            $i++;
        }
        
        return redirect(route('installer.editSystem', $request->name))->with('success', 'System Successfully Updated');
    }
}
