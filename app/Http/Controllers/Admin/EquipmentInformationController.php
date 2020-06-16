<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Equipment\GetEquipDataFields;
use App\Domains\Equipment\SetEquipmentDataFields;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EditFieldRequest;
use App\Http\Requests\Equipment\NewFieldRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EquipmentInformationController extends Controller
{
    public function index()
    {
        return view('admin.equipment.equipmentInformation', [
            'fields' => (new GetEquipDataFields)->getAllFieldsWithStats(),
        ]);
    }

    public function getFields($sysID)
    {
        return (new GetEquipDataFields)->getFieldsForEquip($sysID);
    }

    public function newField(NewFieldRequest $request)
    {
        (new SetEquipmentDataFields)->processNewField($request->name, $request->hidden);
        return response()->json(['success' => true]);
    }

    public function submitFieldName(EditFieldRequest $request)
    {
        (new SetEquipmentDataFields)->editExistingField($request->data_type_id, $request->name, $request->hidden);
        Log::info('Data Field Type ID '.$request->data_type_id.' was updated by '.Auth::user()->full_name.'.  Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    public function deleteField($id)
    {
        (new SetEquipmentDataFields)->deleteField($id);
        Log::notice('Data Field Type ID - '.$id.' was deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
