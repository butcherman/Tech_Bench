<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\SystemCustDataFields;
use App\SystemCustDataTypes;
use App\SystemCategories;
use App\SystemTypes;

class SystemTypesController extends Controller
{
    //  Select a category to add a system to
    public function index()
    {
        $categories = SystemCategories::all();
        
        return view('installer.selectSystemCat', [
            'cats' => $categories
        ]);
    }
    
    //  Open the new system form
    public function newSys($cat)
    {
        $dropDown = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        
        return view('installer.form.newSystem', [
            'cat'      => $cat,
            'dropDown' => $dropDown
        ]);
    }
    
    //  Submit the new system form
    public function submitSys(Request $request, $cat)
    {
        $catID = SystemCategories::where('name', urldecode($cat))->first()->cat_id;
        $sysData = SystemTypes::create([
            'cat_id' => $catID,
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
        
        Log::info('New System Created', ['cat_id' => $request->catName, 'sys_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'System Successfully Added');//
    }

    //  Pick a system to edit
    public function show($id)
    {
        $systems = SystemCategories::with('SystemTypes')->get();

        return view('installer.selectSystem', [
            'categories' => $systems
        ]);
    }

    //  Edit system form
    public function edit($id)
    {
        $sysData = SystemTypes::where('name', urldecode($id))->first();
        
        //  Verify the system we are trying to edit is valid
        if(!$sysData)
        {
            Log::warning('User '.Auth::user()->user_id.' tried to edit invalid system '.$id);
            return view('errors.badSystem');
        }
        
        $dropDown = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        $dataType = SystemCustDataFields::where('sys_id', $sysData->sys_id)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->orderBy('order', 'ASC')
            ->get();
        
        return view('installer.form.editSystem', [
            'dropDown' => $dropDown,
            'fields'   => $dataType,
            'name'     => $sysData->name,
            'sysID'    => $sysData->sys_id
        ]);
    }

    //  Submit the edit system form
    public function update(Request $request, $sysID)
    {
        $sysName = SystemTypes::find($sysID)->name;
        
        //  Verify the system we are trying to edit is valid
        if(!$sysName)
        {
            Log::warning('User '.Auth::user()->user_id.' tried to edit invalid system '.$id);
            return view('errors.badSystem');
        }
        
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
                            SystemCustDataFields::find($type->field_id)->update(
                            [
                                'order' => $i
                            ]);
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
        
        Log::info('System Updated', ['sys_id' => $sysID, 'user_id' => Auth::user()->user_id]);
        
        return redirect(route('installer.systems.edit', $request->name))->with('success', 'System Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
