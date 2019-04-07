<?php

namespace App\Http\Controllers\Installer;

use App\SystemTypes;
use App\SystemCategories;
use App\SystemCustDataTypes;
use Illuminate\Http\Request;
use App\SystemCustDataFields;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SystemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  List the systems that can be modified
    public function index()
    {
        $systems = SystemCategories::with('SystemTypes')->get();
        
        return view('installer.systemsList', [
            'systems' => $systems
        ]);
    }

    //  Open the form to create a new system
    public function create()
    {
        $categories = SystemCategories::all();
        $dataTypes  = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        
        $dropDown = [];
        foreach($dataTypes as $type)
        {
            $dropDown[] = [
                'value' => $type->data_type_id,
                'label' => $type->name
            ];
        }
        
        return view('installer.newSystem', [
            'categories' => $categories,
            'dropDown'   => $dropDown
        ]);
    }

    //  Store the new system type
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|numeric',
            'name' => [
                    'required',
                    'string',
                    Rule::unique('system_types'),
                    'regex:/^[a-zA-Z0-9_ ]*$/'
                ],
            'dataOptions' => 'required'
            
        ]);
        
        $sysData = SystemTypes::create([
            'cat_id'          => $request->category,
            'name'            => $request->name,
            'parent_id'       => null,
            'folder_location' => str_replace(' ', '_', $request->name)
        ]);
        $sysID = $sysData->sys_id;
        $i = 0;
        
        foreach($request->dataOptions as $field)
        {
            if(!empty($field))
            {
                if(isset($field['value']))
                {
                    $id = $field['value'];
                }
                else
                {
                    $newField = SystemCustDataTypes::create([
                        'name' => $field['label']
                    ]);
                    $id = $newField->data_type_id;
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
        $request->session()->flash('success', 'New System Created');
        
        return response()->json(['success' => true]);
    }

    //  Get a JSON array of the system to be edited
    public function show($id)
    {
        $system = SystemTypes::find($id);
        $data   = SystemCustDataFields::where('sys_id', $id)
            ->join('system_cust_data_types', 'system_cust_data_fields.data_type_id', '=', 'system_cust_data_types.data_type_id')
            ->orderBy('order', 'ASC')
            ->pluck('name');
        
        $sysData = [
            'name' => $system->name,
            'data' => $data
        ];
        
        return response()->json($sysData);
    }

    //  Edit an existing system
    public function edit($id)
    {
        $system = SystemTypes::find($id);
        $dataTypes  = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        
        $dropDown = [];
        foreach($dataTypes as $type)
        {
            $dropDown[] = [
                'value' => $type->data_type_id,
                'label' => $type->name
            ];
        }
        
        return view('installer.editSystem', [
            'sys_id'   => $id,
            'name'     => $system->name,
            'dropDown' => $dropDown
        ]);
    }

    //  Update the system data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => [
                    'required',
                    'string',
                    Rule::unique('system_types')->ignore($id, 'sys_id'),
                    'regex:/^[a-zA-Z0-9_ ]*$/'
                ]
        ]);
        
        //  Update the system name
        $sys = SystemTypes::find($id)->update([
            'name' => $request->name
        ]);
        
        //  Update the order of the existing data fields
        $i = 0;
        foreach($request->dataOptions as $data)
        {
            $dataID = SystemCustDataTypes::where('name', $data)->first();
            
            $dataType = SystemCustDataFields::where('sys_id', $id)->where('data_type_id', $dataID->data_type_id)->update([
                'order' => $i
            ]);
            
            $i++;
        }
        
        //  Process any new data fields
        if(!empty($request->newDataOptions))
        {
            foreach($request->newDataOptions as $field)
            {
                if(!empty($field))
                {
                    if(isset($field['value']))
                    {
                        $dataID = $field['value'];
                    }
                    else
                    {
                        $newField = SystemCustDataTypes::create([
                            'name' => $field['label']
                        ]);
                        $dataID = $newField->data_type_id;
                    }

                    SystemCustDataFields::create([
                        'sys_id' => $id,
                        'data_type_id' => $dataID,
                        'order' => $i
                    ]);
                    $i++;
                }
            }
        }
        
        Log::info('System Updated', ['sys_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        $request->session()->flash('success', 'System Updated');
        
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        //
//    }
}
