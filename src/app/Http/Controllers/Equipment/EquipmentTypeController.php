<?php

namespace App\Http\Controllers\Equipment;

use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use App\Features\PublicTechTipFeature;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Service\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EquipmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Index', [
            'equipment-list' => fn() => Cache::equipmentCategories(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'category-list' => fn() => Cache::equipmentCategories(),
            'data-list' => fn() => DataFieldType::all()->pluck('name'),
            'public-tips' => fn() => $request->user()
                ->features()
                ->active(PublicTechTipFeature::class),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentTypeRequest $request)
    {
        $newEquipment = EquipmentType::create($request->only(['cat_id', 'name', 'allow_public_tip']));
        $request->processCustomerFields($newEquipment);

        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        Log::info(
            'New Equipment Type created by ' . $request->user()->username,
            $request->toArray()
        );

        return redirect(route('equipment.index'))->with('success', __('equipment.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentType $equipment)
    {
        $this->authorize('viewAny', $equipment);

        return Inertia::render('Equipment/Show', [
            // TODO - Add Tech Tip References
            'equipment' => $equipment->load(['Customer'/** 'TechTip' */]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, EquipmentType $equipment)
    {
        $this->authorize('update', $equipment);

        return Inertia::render('Equipment/Edit', [
            'equipment' => fn() => $equipment->load('DataFieldType'),
            'category-list' => fn() => Cache::equipmentCategories(),
            'data-list' => fn() => DataFieldType::all()->pluck('name'),
            'public-tips' => fn() => $request->user()
                ->features()
                ->active(PublicTechTipFeature::class),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentTypeRequest $request, EquipmentType $equipment)
    {
        $equipment->update($request->only(['cat_id', 'name', 'allow_public_tip']));
        $request->processCustomerFields($equipment);

        Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        Log::info('Equipment Type ' . $equipment->name . ' updated by ' .
            $request->user()->username, $request->toArray());

        UpdateCustomerDataFieldsJob::dispatch($equipment);

        return redirect(route('equipment.index'))->with('success', __('equipment.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, EquipmentType $equipment)
    {
        $this->authorize('delete', $equipment);

        try {
            $equipment->delete();
            Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(
                    $equipment->name . ' is still in use and cannot be deleted',
                    0,
                    $e
                );
            } else {
                // @codeCoverageIgnoreStart
                throw new GeneralQueryException('', 0, $e);
                // @codeCoverageIgnoreEnd
            }
        }

        Log::notice('Equipment Type ' . $equipment->name . ' was deleted by ' .
            $request->user()->username);

        return redirect(route('equipment.index'))->with('warning', __('equipment.destroyed'));
    }
}
