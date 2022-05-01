<?php

namespace App\Http\Controllers\Equipment;

use App\Actions\OrderEquipDataTypes;
use App\Events\Equipment\EquipmentTypeCreatedEvent;
use App\Events\Equipment\EquipmentTypeDeletedEvent;
use App\Events\Equipment\EquipmentTypeUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Models\CustomerEquipment;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentController extends Controller
{
    /**
     * Index method will show all available categories and equipment types
     */
    public function index()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Index', [
            'categories' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Store a newly created Equipment Type
     */
    public function store(EquipmentTypeRequest $request)
    {
        $newEquip = EquipmentType::create([
            'cat_id' => EquipmentCategory::where('name', $request->category)->first()->cat_id,
            'name'   => $request->name,
        ]);

        (new OrderEquipDataTypes)->run($request->data_fields, $newEquip->equip_id);

        event(new EquipmentTypeCreatedEvent($newEquip));
        return redirect(route('equipment.index'))->with([
            'message' => 'New Equipment Created',
            'type'    => 'success',
        ]);
    }

    /**
     * Show the form for creating a new Equipment Type
     */
    public function show($id)
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'category'  => EquipmentCategory::find($id)->name,
            'cat_list'  => EquipmentCategory::all(),
            'data_list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Show the form for editing the equipment Type
     */
    public function edit($id)
    {
        $equip = EquipmentType::with(['EquipmentCategory', 'DataFieldType'])->findOrFail($id);
        $this->authorize('update', $equip);

        return Inertia::render('Equipment/Edit', [
            'equipment' => $equip,
            'cat_list'  => EquipmentCategory::all(),
            'dataList'  => DataFieldType::all()->pluck('name'),
            'in_use'    => CustomerEquipment::where('equip_id', $id)->count() > 0 ? true : false,
        ]);
    }

    /**
     * Update the Equipment Type
     */
    public function update(EquipmentTypeRequest $request, $id)
    {
        $equip = EquipmentType::findOrFail($id);
        $equip->update([
            'cat_id' => EquipmentCategory::where('name', $request->category)->first()->cat_id,
            'name'   => $request->name,
        ]);

        $dataTypeObj = new OrderEquipDataTypes;
        $dataTypeObj->delOptions($request->del_fields, $equip->equip_id);
        $dataTypeObj->run($request->data_fields, $equip->equip_id);

        event(new EquipmentTypeUpdatedEvent($equip));
        return redirect(route('equipment.index'))->with([
            'message' => 'Equipment Updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove the Equipment Type
     */
    public function destroy($id)
    {
        $equip = EquipmentType::findOrFail($id);
        $this->authorize('delete', $equip);
        $equip->delete();

        event(new EquipmentTypeDeletedEvent($equip));
        return redirect(route('equipment.index'))->with([
            'message' => 'Equipment Deleted',
            'type'    => 'danger',
        ]);
    }
}
