<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Domains\Equipment\GetEquipment;
use App\Domains\Equipment\SetEquipment;
use App\Domains\Equipment\GetEquipDataFields;

use App\Http\Requests\Equipment\EquipmentRequest;

class EquipmentTypesController extends Controller
{
    //  Home page shows all available categories and equipment
    public function index()
    {
        return view('admin.equipment.index', [
            'equip' => (new GetEquipment)->getAllEquipment(true),
        ]);
    }

    //  Form to create a new equipment type
    public function create()
    {
        return view('admin.equipment.equipmentForm', [
            'data'   => null,
            'fields' => (new GetEquipDataFields)->getAllFields(),
            'cats'   => (new GetEquipment)->getCatList(),
        ]);
    }

    //  Submit the new equipment form
    public function store(EquipmentRequest $request)
    {
        (new SetEquipment)->createEquipment($request);
        Log::notice('New Equipment has been created by '.Auth::user()->full_name.'. Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  View the form to update equipment
    public function edit($id)
    {
        return view('admin.equipment.equipmentForm', [
            'data'   => (new GetEquipment)->getEquipmentData($id),
            'fields' => (new GetEquipDataFields)->getAllFields(),
            'cats'   => null,
        ]);
    }

    //  Submit the form to update equipment
    public function update(EquipmentRequest $request, $id)
    {
        $result = (new SetEquipment)->updateEquipment($request, $id);

        if(is_array($result))
        {
            Log::error('Unable to remove Field '.$result[0]['data_field_name'].' - it is still in use by some customers');
            abort(428, 'Unable to remove Field '.$result[0]['data_field_name'].' - it is still in use by some customers');
        }

        Log::notice('Equipment ID '.$id.' has been updated by '.Auth::user()->full_name.'. Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Delete an equipment type
    public function destroy($id)
    {
        $res = (new SetEquipment)->deleteEquipment($id);

        if(!$res)
        {
            abort(428, 'Unable to remove Equipment - it is still in use by some Customers or Tech Tips');
        }

        Log::notice('Equipment ID '.$id.' has been deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
