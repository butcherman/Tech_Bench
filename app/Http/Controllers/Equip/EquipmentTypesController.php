<?php

namespace App\Http\Controllers\Equip;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentTypesController extends Controller
{
    /**
     *  Show the list of Equipment Types that can be edited
     */
    public function index()
    {
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

        return redirect()->route('admin.index')->with(['message' => 'New Equipment Created Successfully', 'type' => 'success']);
    }

    /**
     *  Form to edit existing equipment
     */
    public function edit($id)
    {
        $this->authorize('update', EquipmentType::class);

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

        // return $request->data_fields;
//  TODO - FInish updating existing equipment
        //  Update the existing customer fields
        // foreach($request->data_fields as $field)
        // {
        //     if($field !== null)
        //     {
        //         //  Determine if this is a new or existing field type
        //         $fieldID  = DataFieldType::where('name', $field)->first();
        //         if(!$fieldID)
        //         {
        //             $fieldID = DataFieldType::create(['name' => $field]);
        //         }

        //         //  Attach the field to the equipment type
        //         // DataField::updateOrCreate(
        //         // [
        //         //     'equip_id' => $id,
        //         //     'type_id'  => $fieldID->type_id,
        //         // ],
        //         // [
        //         //     'order'    => $order,
        //         // ]);

        //         // $order++;
        //     }
        // }

        return redirect()->route('admin.index')->with(['message' => 'Equipment Updated Successfully', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
