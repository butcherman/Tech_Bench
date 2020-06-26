<?php

namespace App\Domains\Equipment;

use App\SystemTypes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetEquipment extends SetEquipmentDataFields
{
    //  build a new equipment type
    public function createEquipment($request)
    {
        $newEquip = SystemTypes::create([
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'folder_location' => $request->name,
        ]);
        $this->createEquipmentFields($request->system_data_fields, $newEquip->sys_id);

        return true;
    }

    //  Update the name of an equipment type
    public function updateEquipment($request, $sysID)
    {
        $equip = SystemTypes::findOrFail($sysID);
        DB::beginTransaction();

        $equip->update([
            'name'   => $request->name,
        ]);
        $success = $this->updateEquipFields($request->system_data_fields, $sysID);
        if(is_array($success))
        {
            Log::error('Unaable to Update Equpiment information.  Reason - ', $success);
            DB::rollBack();
        }
        else
        {
            DB::commit();
        }

        return $success;
    }

    //  Delete an equipment type - note cannot delete if the equipment is in use
    public function deleteEquipment($sysID)
    {
        try
        {
            SystemTypes::find($sysID)->delete();
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            Log::error('Unable to delete Equipment ID '.$this->sysID.'.  Reason - ', array($e));
            return false;
        }

        return true;
    }
}
