<?php

namespace App\Http\Controllers\Equipment;

use App\Actions\OrderEquipDataTypes;
use App\Exceptions\RecordInUseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Equipment types that can be assigned to customers and Tech Tips
 */
class EquipmentController extends Controller
{
    /**
     * Display a listing of all Equipment types
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Index', [
            'equip-list' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Show the form for creating a new Equipment Type
     */
    public function create()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'categories' => EquipmentCategory::all(),
            'data-list' => DataFieldType::all()->pluck('name'),
        ]);
    }

    /**
     * Store a newly created Equipment Type
     */
    public function store(EquipmentRequest $request)
    {
        $newEquip = EquipmentType::create($request->only(['cat_id', 'name']));
        (new OrderEquipDataTypes)->build($request->custData, $newEquip->equip_id);

        Log::info('New Equipment Type '.$request->name.' created by '.$request->user()->username, $request->toArray());

        return redirect(route('equipment.index'))->with('success', __('equipment.created'));
    }

    /**
     * Display the Equipment Type along with all references to it
     */
    public function show(EquipmentType $equipment)
    {
        $this->authorize('viewAny', $equipment);

        return Inertia::render('Equipment/Show', [
            'equipment' => $equipment->load(['Customer', 'TechTip']),
        ]);
    }

    /**
     * Show the form for editing the Equipment Type and its Data Types
     */
    public function edit(EquipmentType $equipment)
    {
        $this->authorize('update', $equipment);

        return Inertia::render('Equipment/Edit', [
            'categories' => EquipmentCategory::all(),
            'data-list' => DataFieldType::all()->pluck('name'),
            'equipment' => $equipment->load('DataFieldType'),
        ]);
    }

    /**
     * Update the specified Equipment Type and Data Types for that equipment
     */
    public function update(EquipmentRequest $request, EquipmentType $equipment)
    {
        $equipment->update($request->only(['cat_id', 'name']));
        (new OrderEquipDataTypes)->build($request->custData, $equipment->equip_id);

        Log::info('Equipment Type '.$request->name.' has been updated by '.$request->user()->username, $request->toArray());

        return redirect(route('equipment.index'))->with('success', __('equipment.updated'));
    }

    /**
     * Remove the specified Equipiment Type
     * Note:  Equipment cannot be deleted if it is in use
     */
    public function destroy(Request $request, EquipmentType $equipment)
    {
        $this->authorize('delete', $equipment);

        try {
            $equipment->delete();
        } catch (QueryException $e) {
            //  If the model is still in use, throw a unique exception
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(__('equipment.in-use', ['name' => $equipment->name]), 0, $e);
            }

            // @codeCoverageIgnoreStart
            Log::error('Error when trying to delete Equipment '.$equipment->name, $e->errorInfo);

            return back()->withErrors(['error' => 'failed']);
            // @codeCoverageIgnoreEnd
        }

        Log::notice('Equipment Type '.$equipment->name.' was deleted by '.$request->user()->username);

        return redirect(route('equipment.index'))->with('warning', __('equipment.destroyed'));
    }
}
