<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Equipment\GetEquipDataFields;
use App\Domains\Equipment\SetEquipmentDataFields;

use App\Http\Controllers\Controller;

use App\Http\Requests\Equipment\NewFieldRequest;
use App\Http\Requests\Equipment\EditFieldRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EquipmentInformationController extends Controller
{
    //  Index/landing page will show all possible customer data fields that can be assigned to a customer's equipment and which equipment it is currently assigned to
    public function index()
    {
        return view('admin.equipment.equipmentInformation', [
            'fields' => (new GetEquipDataFields)->getAllFieldsWithStats(),
        ]);
    }

    //  Get all of the fields for a specific piece of equipment
    public function getFields($sysID)
    {
        return (new GetEquipDataFields)->getFieldsForEquip($sysID);
    }

    //  Submit a new field for a customer's equipment
    public function newField(NewFieldRequest $request)
    {
        (new SetEquipmentDataFields)->processNewField($request->name, $request->hidden);
        return response()->json(['success' => true]);
    }

    //  Update an existing fields name
    public function submitFieldName(EditFieldRequest $request)
    {
        (new SetEquipmentDataFields)->editExistingField($request->data_type_id, $request->name, $request->hidden);
        Log::info('Data Field Type ID '.$request->data_type_id.' was updated by '.Auth::user()->full_name.'.  Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Delete an existing field  note - if the field is in use, the delete will fail
    public function deleteField($id)
    {
        (new SetEquipmentDataFields)->deleteField($id);
        Log::notice('Data Field Type ID - '.$id.' was deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
