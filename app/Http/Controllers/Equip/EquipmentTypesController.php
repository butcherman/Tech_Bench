<?php

namespace App\Http\Controllers\Equip;

use Inertia\Inertia;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Models\EquipmentCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EquipmentTypesController extends Controller
{
    /**
     *  Show the list of Equipment Types that can be edited
     */
    public function index()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/listEquipment', [
            'equipmentList' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     *  Create new equipment form
     */
    public function create()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/createEquipment', [
            'categories' => EquipmentCategory::all(),
            'dataList'  => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     *  Store the new equipment type
     */
    public function store(EquipmentTypeRequest $request)
    {
        $order = 0;

        //  Create the new equipment
        $newEquip = EquipmentType::create([
            'cat_id' => EquipmentCategory::where('name', $request->cat_id)->first()->cat_id,
            'name'   => $request->name,
        ]);

        //  Input the customer information
        foreach($request->data_fields as $field)
        {
            if($field !== null)
            {
                //  Determine if this is a new or existing field type
                $fieldID  = DataFieldType::where('name', $field)->first();
                if(!$fieldID)
                {
                    $fieldID = DataFieldType::create(['name' => $field]);
                }

                //  Attach the field to the equipment type
                DataField::create([
                    'equip_id' => $newEquip->equip_id,
                    'type_id'  => $fieldID->type_id,
                    'order'    => $order,
                ]);

                $order++;
            }
        }

        Log::info('New Equipment ID '.$newEquip->equip_id.' name - '.$newEquip->name.' has been created by '.Auth::user()->full_name);
        return redirect()->route('admin.index')->with(['message' => 'New Equipment Created Successfully', 'type' => 'success']);
    }

    /**
     *  Form to edit existing equipment
     */
    public function edit($id)
    {
        $this->authorize('update', EquipmentType::find($id));

        return Inertia::render('Equipment/editEquipment', [
            'categories' => EquipmentCategory::all(),
            'dataList'   => DataFieldType::all()->pluck('name'),
            'equipment'  => EquipmentType::with('EquipmentCategory')->with('DataFieldType')->findOrFail($id),
        ]);
    }

    /**
     *  Update Equipment
     */
    public function update(EquipmentTypeRequest $request, $id)
    {
        $order = 0;

        //  Create the new equipment
        EquipmentType::findOrFail($id)->update([
            'cat_id' => EquipmentCategory::where('name', $request->cat_id)->first()->cat_id,
            'name'   => $request->name,
        ]);

        //  Update the existing customer fields
        foreach($request->data_fields as $field)
        {
            if($field !== null)
            {
                //  Determine if this is a new or existing field type
                $fieldID  = DataFieldType::where('name', $field)->first();
                if(!$fieldID)
                {
                    $fieldID = DataFieldType::create(['name' => $field]);
                }

                //  Attach the field to the equipment type
                DataField::updateOrCreate(
                [
                    'equip_id' => $id,
                    'type_id'  => $fieldID->type_id,
                ],
                [
                    'order'    => $order,
                ]);

                $order++;
            }

            //  Remove any fields that were deleted
            foreach($request->del_fields as $deleted)
            {
                $fieldID = DataFieldType::where('name', $deleted)->first();
                DataField::where(['equip_id' => $id, 'type_id' => $fieldID->type_id])->delete();
            }
        }

        return redirect()->route('admin.index')->with(['message' => 'Equipment Updated Successfully', 'type' => 'success']);
    }

    /**
     *  Remove equipment from the database
     */
    public function destroy($id)
    {
        $this->authorize('delete', EquipmentType::find($id));

        //  TODO - Add checks to make sure it can be deleted
        $equip = EquipmentType::find($id);


        Log::notice('Equipment ID '.$id.' Name - '.$equip->name.' has been deleted by '.Auth::user()->full_name);
        $equip->delete();

        return redirect()->route('admin.index')->with(['message' => 'Equipment Deleted Successfully', 'type' => 'success']);
    }
}