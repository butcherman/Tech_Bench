<?php

namespace App\Http\Controllers\Installer;

use App\SystemTypes;
use App\SystemDataFields;
use App\SystemCategories;
use Illuminate\Http\Request;
use App\SystemDataFieldTypes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class SystemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->authorize('hasAccess', 'Manage Equipment');
            return $next($request);
        });
    }

    //  List the systems that can be modified
    public function index()
    {
        $systems = SystemCategories::with('SystemTypes')->get();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Fetched Data - ', $systems->toArray());
        return view('installer.systemsList', [
            'systems' => $systems
        ]);
    }

    //  Open the form to create a new system
    // public function create()
    // {
    //     $categories = SystemCategories::all();
    //     $dataTypes  = SystemCustDataTypes::orderBy('name', 'ASC')->get();

    //     $dropDown = [];
    //     foreach($dataTypes as $type)
    //     {
    //         $dropDown[] = [
    //             'value' => $type->data_type_id,
    //             'label' => $type->name
    //         ];
    //     }

    //     Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
    //     return view('installer.newSystem', [
    //         'categories' => $categories,
    //         'dropDown'   => $dropDown
    //     ]);
    // }

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
            'folder_location' => str_replace(' ', '_', $request->name)
        ]);
        $sysID = $sysData->sys_id;
        $i = 0;

        foreach($request->dataOptions as $field)
        {
            if(!empty($field))
            {
                if(isset($field['data_type_id']))
                {
                    $id = $field['data_type_id'];
                }
                else
                {
                    $newField = SystemDataFieldTypes::create([
                        'name' => $field['name']
                    ]);
                    $id = $newField->data_type_id;
                }

                SystemDataFields::create([
                    'sys_id' => $sysID,
                    'data_type_id' => $id,
                    'order' => $i
                ]);
                $i++;
            }
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('New System Created', ['cat_id' => $request->catName, 'sys_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        $request->session()->flash('success', 'New System Created');

        return response()->json(['success' => true]);
    }

    //  Open the form to add equipment for the sepcified category
    public function show($cat)
    {
        $fields = SystemDataFieldTypes::all();
        $cat = SystemCategories::where('name', str_replace('-', ' ', $cat))->first();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('installer.newSystem', [
            'cat'      => $cat,
            'dataList' => $fields,
        ]);
    }

    //  Edit an existing system
    public function edit($id)
    {
        $fields = SystemDataFieldTypes::all();
        $system = SystemTypes::where('sys_id', $id)->with(['SystemDataFields' => function($query) {
            $query->join('system_data_field_types', 'system_data_fields.data_type_id', '=', 'system_data_field_types.data_type_id');
        }])->withCount('SystemDataFields')->first();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('installer.editSystem', [
            'system'   => $system,
            'dataList' => $fields,
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
                    'regex:/^[-a-zA-Z0-9_ ]*$/'
            ],
            'dataOptions' => 'required',
        ]);

        //  Update the system name
        SystemTypes::find($id)->update([
            'name' => $request->name
        ]);

        //  Update the order of the existing data fields
        $i = 0;
        foreach($request->dataOptions as $data)
        {
            SystemDataFields::where('sys_id', $id)->where('data_type_id', $data['data_type_id'])->update([
                'order' => $i
            ]);

            $i++;
        }
        //  Process any new data fields
        if(!empty($request->newOptions))
        {
            foreach($request->newOptions as $field)
            {
                if(!empty($field))
                {
                    if(isset($field['data_type_id']))
                    {
                        $dataID = $field['data_type_id'];
                    }
                    else
                    {
                        $newField = SystemDataFieldTypes::create([
                            'name' => $field['name']
                        ]);
                        $dataID = $newField->data_type_id;
                    }

                    SystemDataFields::create([
                        'sys_id' => $id,
                        'data_type_id' => $dataID,
                        'order' => $i
                    ]);
                    $i++;
                }
            }
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('System Updated', ['sys_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        $request->session()->flash('success', 'System Updated');

        return response()->json(['success' => true]);
    }

    //  Delete an existing system - note this will fail if the system has any customers or tech tips assigned to it
    public function destroy($id)
    {
        //
        try
        {
            SystemTypes::find($id)->delete();
            return response()->json(['success' => true, 'reason' => 'Equipment Successfully Deleted']);
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return response()->json(['success' => false, 'reason' => 'Cannot delete this equipment.  It has Customers or Tech Tips assigned to it.  Please delete those first.']);
        }
    }
}
