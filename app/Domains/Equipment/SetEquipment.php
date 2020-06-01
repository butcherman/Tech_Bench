<?php

namespace App\Domains\Equipment;

use App\SystemTypes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SetEquipment extends SetEquipmentDataFields
{
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
            DB::rollBack();
            // abort(428, 'Unable to remove Field '.$success[0]['data_field_name'].' - it is still in use by some customers');
        }
        DB::commit();
        return $success;
    }

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
