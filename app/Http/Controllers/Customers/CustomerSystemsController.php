<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\SystemTypes;
use App\CustomerSystems;
use Illuminate\Http\Request;    
use App\CustomerSystemFields;
use App\SystemCustDataFields;
use App\Http\Traits\SystemsTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class CustomerSystemsController extends Controller
{    
    use SystemsTrait;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new system for the customer
    public function store(Request $request)
    {
        $request->validate([
            'custID' => 'required',
            'system' => 'required'
        ]);
        
        //  Insert the system into the DB
        $sys = CustomerSystems::create([
            'cust_id' => $request->custID,
            'sys_id'  => $request->system
        ]);
        
        //  Get the data fields for the new system
        $fields = SystemCustDataFields::where('sys_id', $request->system)->get();
                
        //  Enter each of the data fields into the DB
        foreach($fields as $field)
        {
            $sysFields = CustomerSystemFields::create([
                'cust_sys_id' => $sys->cust_sys_id,
                'field_id'    => $field->field_id,
                'value'       => isset($request->fieldData[$field->field_id]) ? $request->fieldData[$field->field_id] : null
            ]);
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('New Customer System Added - Customer ID-'.$request->custID.' System ID-'.$request->system);
        Log::debug('Submitted System Data', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Get the list of systems attached to the customer
    public function show($id)
    {
        $sysList = CustomerSystems::where('cust_id', $id)->get();
        
        $sysArr = [];
        foreach($sysList as $sys)
        {
            //  Pull all system data
            $sysName = SystemTypes::find($sys->sys_id)->name;
            $sysData = CustomerSystemFields::where('cust_sys_id', $sys->cust_sys_id)
                    ->leftJoin('system_cust_data_fields', 'customer_system_fields.field_id', '=', 'system_cust_data_fields.field_id')
                    ->leftJoin('system_cust_data_types', 'system_cust_data_fields.data_type_id', '=', 'system_cust_data_types.data_type_id')
                    ->orderBy('order', 'asc')
                    ->get();
            
            //  Sort the data attached to the system
            $dataArr = [];
            $dataArr[] = [
                'name'  => 'System Type',
                'value' => $sysName,
                'id'    => 0
            ];
            foreach($sysData as $data)
            {
                $dataArr[] = [
                    'name'  => $data->name,
                    'value' => $data->value,
                    'id'    => $data->id
                ];
            }
                
            //  Populate the system array
            $sysArr[] = [
                'sys_id'      => $sys->sys_id,
                'name'        => $sysName,
                'cust_sys_id' => $sys->cust_sys_id,
                'data'        => $dataArr
            ];
        }
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Show Data', $sysArr);
        return response()->json($sysArr);
    }
    
    //  Get the data fields attached to a system
    public function getDataFields($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return response()->json($this->getFields($id));
    }

    //  Update the customers system data
    public function update(Request $request, $id)
    {
        $request->validate([
            'custID'    => 'required',
            'system'    => 'required',
            'fieldData' => 'required'
        ]);
        
        foreach($request->fieldData as $data)
        {
            if($data['id'] != 0)
            {
                CustomerSystemFields::find($data['id'])->update([
                    'value' => !empty($data['value']) ? $data['value'] : null
                ]);
            }
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('Customer System Updated.  Cust ID-'.$request->custID.' System ID-'.$request->sysstem.' User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ID:'.$id.' - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Delete a system attached to a customer
    public function destroy($id)
    {
        $system = CustomerSystems::find($id);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('Customer System Deleted for Customer ID-'.$system->cust_id.' by User ID-'.Auth::user()->user_id.'. System ID-'.$id);
        Log::debug('System Data', $system->toArray());
        
        $system->delete();
        
        return response()->json(['success' => true]);
    }
}
