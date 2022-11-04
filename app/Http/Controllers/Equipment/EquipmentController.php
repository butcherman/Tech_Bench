<?php

namespace App\Http\Controllers\Equipment;

use App\Actions\OrderEquipDataTypes;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    /**
     * Landing page for the Equipment Admin Page
     */
    public function index()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Index', [
            'cat-list' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Show the form for creating a new Equipment type
     */
    public function create(EquipmentCategory $equipmentCategory)
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'category'  => $equipmentCategory,
            'data-list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Store a newly created Equipment Type
     */
    public function store(EquipmentTypeRequest $request)
    {
        $equipment         = new EquipmentType($request->only(['name']));
        $equipment->cat_id = EquipmentCategory::where('name', $request->category)->first()->cat_id;
        $equipment->save();

        (new OrderEquipDataTypes)->build($request->custData, $equipment->equip_id);
        Log::info('New Equipiment Type created by '.$request->user()->username, $request->toArray());

        return redirect(route('equipment.index'))->with('success', __('equip.created'));
    }

    /**
     * Show all references that a piece of equipment has
     */
    public function show(EquipmentType $equipment)
    {
        return 'show';
    }

    /**
     * Show the form for editing the Equipment
     */
    public function edit(EquipmentType $equipment)
    {
        $this->authorize('update', $equipment);

        return Inertia::render('Equipment/Edit', [
            'equipment' => $equipment->load(['EquipmentCategory', 'DataFieldType']),
            'data-list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Update the specified equipment
     */
    public function update(EquipmentTypeRequest $request, EquipmentType $equipment)
    {
        $equipment->name = $request->name;
        $equipment->save();

        (new OrderEquipDataTypes)->build($request->custData, $equipment->equip_id);



        return redirect(route('equipment.index'))->with('success', __('equip.updated'));
    }

    /**
     * Remove the specified equipment from the DB
     */
    public function destroy(EquipmentType $equipment)
    {
        $this->authorize('delete', $equipment);

        try
        {
            $equipment->delete();
        }
        catch(QueryException $e)
        {
            if($e->errorInfo[1] === 19)
            {
                Log::error('Unable to delete Equipment '.$equipment->name.'.  It is currently in use');
                return back()->withErrors([
                    'error' => __('equip.in_use'),
                    // 'link'  => '<a html="#">More Info</a>',
                ]);
            }

            // @codeCoverageIgnoreStart
            Log::error('Error when trying to delete Equipment '.$equipment->name, $e->errorInfo);
            return back()->withErrors(['error' => __('equip.del_failed')]);
            // @codeCoverageIgnoreEnd
        }

        Log::notice('Equipment '.$equipment->name.' has been deleted by '.Auth::user()->username);
        return redirect(route('equipment.index'))->with('success', __('equip.destroyed'));
    }
}
