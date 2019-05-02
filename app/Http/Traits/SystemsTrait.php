<?php

namespace App\Http\Traits;

use App\SystemTypes;
use App\SystemCustDataFields;
use Illuminate\Support\Facades\Log;

trait SystemsTrait
{
    //  Get a list of all systems
    public function getAllSystems()
    {
        $sysList = SystemTypes::select('system_types.*', 'system_categories.name AS cat_name')
            ->join('system_categories', 'system_types.cat_id', '=', 'system_categories.cat_id')
            
            ->orderBy('cat_name', 'ASC')
            ->get();
        
        $sysArr = [];
        foreach($sysList as $sys)
        {
            $sysArr[$sys->cat_name][] = [
                'sys_id' => $sys->sys_id,
                'name'   => $sys->name
            ];
        }
        
        return $sysArr;
    }
    
    //  Get the data fields attached to a system
    public function getFields($id)
    {
        $sysFields = SystemCustDataFields::where('sys_id', $id)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->select('system_cust_data_types.data_type_id', 'system_cust_data_types.name', 'field_id', 'hidden', 'order')
            ->orderBy('order', 'ASC')
            ->get();
        
        return $sysFields;
    }
}
