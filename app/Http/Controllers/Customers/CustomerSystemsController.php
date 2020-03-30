<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\CustomerSystems;
use App\SystemDataFields;
use App\SystemCategories;
use App\CustomerSystemData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\SystemCategoriesCollection as CategoriesCollection;

class CustomerSystemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Get the possible system types that can be assigned to the customer
    public function index()
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);
        $sysList = new CategoriesCollection(SystemCategories::with('SystemTypes')->with('SystemTypes.SystemDataFields.SystemDataFieldTypes')->get());

        return $sysList;
    }

    //  Store a new system for the customer
    public function store(Request $request)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'cust_id' => 'required',
            'system'  => 'required'
            //  TODO:  validate system is unique to customer (write a test for it)
        ]);

        //  Determine if the system is supposed to be added for the parent, or this site
        $details = Customers::find($request->cust_id);
        if($details->parent_id && $request->shared == 1)
        {
            $request->cust_id = $details->parent_id;
        }

        //  Insert the system into the DB
        $sys = CustomerSystems::create([
            'cust_id' => $request->cust_id,
            'sys_id'  => $request->system,
            'shared'  => $request->shared,
        ]);

        //  Get the data fields for the new system
        $fields = SystemDataFields::where('sys_id', $request->system)->get();

        //  Enter each of the data fields into the DB
        foreach($fields as $field)
        {
            $data = 'field_'.$field->field_id;
            CustomerSystemData::create([
                'cust_sys_id' => $sys->cust_sys_id,
                'field_id'    => $field->field_id,
                'value'       => isset($request->$data) ? $request->$data : null
            ]);
        }

        Log::info('New Customer System Added by '.Auth::user()->user_id.' - Customer ID - '.$request->cust_id.' System ID - '.$request->system);
        return response()->json(['success' => true]);
    }

    //  Get the list of systems attached to the customer
    public function show($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $sysList = CustomerSystems::where('cust_id', $id)
                    ->with('SystemTypes')
                    ->with('SystemDataFields')
                    ->with('SystemDataFields.SystemDataFieldTypes')
                    ->orderBy('cust_sys_id', 'DESC')
                    ->get();

        //  determine if there is a parent site with shared systems
        $parent = Customers::findOrFail($id)->parent_id;
        if($parent != null)
        {
            $parentList = CustomerSystems::where('cust_id', $parent)
                                ->where('shared', 1)
                                ->with('SystemTypes')
                                ->with('SystemDataFields')
                                ->with('SystemDataFields.SystemDataFieldTypes')
                                ->get();

            $sysList = $sysList->merge($parentList);
        }

        return $sysList;
    }

    // Update the customers system data
    public function update(Request $request, $id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'cust_id'    => 'required',
            'system'    => 'required',
        ]);

        //  Verify the system type and customer ID match
        $valid = CustomerSystems::where('cust_id', $request->cust_id)->where('cust_sys_id', $id)->first();

        if(!$valid)
        {
            return abort(400);
        }

        $fields = SystemDataFields::where('sys_id', $request->system)->get();

        foreach($fields as $data)
        {
            $fieldName = 'field_' . $data->field_id;
            if(isset($request->$fieldName))
            {
                Log::debug($request->$fieldName);
            }
            Log::debug($fieldName);
            CustomerSystemData::where('cust_sys_id', $id)->where('field_id', $data->field_id)->update([
                'value'       => isset($request->$fieldName) ? $request->$fieldName : null
            ]);
        }

        Log::info('Customer System Updated.  Cust ID-'.$request->custID.' System ID-'.$request->sysstem.' by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Delete a system attached to a customer
    public function destroy($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        // return response('deleted '.$id);
        $system = CustomerSystems::find($id);

        Log::notice('Customer System Deleted for Customer ID-'.$system->cust_id.' by '.Auth::user()->full_name.'. System ID-'.$id);

        $system->delete();

        return response()->json(['success' => true]);
    }
}
