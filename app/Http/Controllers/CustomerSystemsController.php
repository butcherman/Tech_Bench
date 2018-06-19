<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemTypes;
use App\SystemCategories;
use App\SystemCustDataFields;
use App\CustomerSystems;
use App\CustomerSystemFields;

class CustomerSystemsController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // New system form
    public function create()
    {
        $systems = SystemCategories::with('SystemTypes')
            ->orderBy('cat_id', 'asc')
            ->get();
        
        $sysArr = [];
        foreach($systems as $sys)
        {
            foreach($sys->SystemTypes as $s)
            {
                $sysArr[$sys->name][$s->sys_id] = $s->name;
            }
        }
        
        return view('customer.form.addSystem', [
            'systems' => $sysArr
        ]);
    }

    //  Store the new customer system
    public function store(Request $request)
    {
        $request->validate(['sysType' => 'required']);
        
        
        $newSys = CustomerSystems::create([
            'cust_id' => $request->cust_id,
            'sys_id' => $request->sysType
        ]);
        $newSysID = $newSys->cust_sys_id;
        
        foreach($request->field as $key => $field)
        {
            $fieldID = SystemCustDataFields::where('data_type_id', $key)->where('sys_id', $request->sysType)->first()->field_id;
            
            CustomerSystemFields::create([
                'cust_sys_id' => $newSysID,
                'field_id' => $fieldID,
                'value' => $field
            ]);
        }
        
        return 'success';
    }
    
    //  Check to see if the customer already has the system
    public function checkSys($custID, $sysID)
    {
        $custSystems = CustomerSystems::where('cust_id', $custID)->where('sys_id', $sysID);

        return $custSystems->count();
    }

    // Load Customer Systems
    public function show($id)
    {
        $custSystems = CustomerSystems::where('cust_id', $id)->get();

        switch($custSystems->count())
        {
            case 0:
                return view('customer.system_none');
                break;
            case 1:
                $custSysID = $custSystems->first()->cust_sys_id;
                $sysName   = SystemTypes::find($custSystems->first()->sys_id)->name;
                $systemData = CustomerSystemFields::where('cust_sys_id', $custSysID)
                    ->leftJoin('system_cust_data_fields', 'customer_system_fields.field_id', '=', 'system_cust_data_fields.field_id')
                    ->leftJoin('system_cust_data_types', 'system_cust_data_fields.data_type_id', '=', 'system_cust_data_types.data_type_id')
                    ->orderBy('order', 'asc')
                    ->get();
                return view('customer.system_one', [
                    'sysName' => $sysName,
                    'sysData' => $systemData,
                    'sysID'   => $custSysID
                ]);
                break;
            default:
                $systemData = [];

                foreach($custSystems as $sys)
                {
                    $custSysID = $sys->cust_sys_id;
                    $sysName   = SystemTypes::find($sys->sys_id)->name;
                    $systemData[$sysName] = CustomerSystemFields::where('cust_sys_id', $custSysID)
                        ->leftJoin('system_cust_data_fields', 'customer_system_fields.field_id', '=', 'system_cust_data_fields.field_id')
                        ->leftJoin('system_cust_data_types', 'system_cust_data_fields.data_type_id', '=', 'system_cust_data_types.data_type_id')
                        ->orderBy('order', 'asc')
                        ->get();
                }
                
                return view('customer.system_multi', [
                    'sysData' => $systemData
                ]);
        }
    }

    //  Edit the customers system 
    public function edit($id)
    {
        $custSys = CustomerSystems::find($id);
        $sysName = SystemTypes::find($custSys->sys_id)->name;
        $fields = CustomerSystemFields::where('cust_sys_id', $id)
            ->leftJoin('system_cust_data_fields', 'customer_system_fields.field_id', '=', 'system_cust_data_fields.field_id')
            ->leftJoin('system_cust_data_types', 'system_cust_data_fields.data_type_id', '=', 'system_cust_data_types.data_type_id')
            ->get();
    
        return view('customer.form.editSystem', [
            'sysName'   => $sysName,
            'sysFields' => $fields, 
            'id'        => $id
        ]);
    }

    //  Update the customers system
    public function update(Request $request, $id)
    {
        $custSys = CustomerSystems::find($id);
        $sysID   = $custSys->sys_id;
        
        foreach($request->field as $key => $field)
        {
            $fieldID = SystemCustDataFields::where('data_type_id', $key)->where('sys_id', $sysID)->first()->field_id;
            
            CustomerSystemFields::where('cust_sys_id', $id)->where('field_id', $fieldID)->update(['value' => $field]);
        }
        
        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $system = CustomerSystems::find($id);
        $system->delete();
    }
}
