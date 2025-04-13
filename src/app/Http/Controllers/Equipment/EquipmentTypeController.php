<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheData;
use App\Facades\CacheFacade;
use App\Features\PublicTechTipFeature;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentTypeController extends Controller
{
    public function __construct(protected EquipmentService $svc) {}

    /**
     * Display a listing all Equipment Types grouped by category.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Index', [
            'equipment-list' => fn() => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show the form for creating a new Equipment Type.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'category-list' => fn() => CacheData::equipmentCategories(),
            'data-list' => fn() => CacheData::dataFieldTypes()->pluck('name'),
            'public-tips' => fn() => $request->user()
                ->features()
                ->active(PublicTechTipFeature::class),
        ]);
    }

    /**
     * Store a newly created Equipment Type.
     */
    public function store(EquipmentTypeRequest $request): RedirectResponse
    {
        $this->svc->createEquipmentType($request->safe()->collect());

        return redirect(route('equipment.index'))
            ->with('success', __('equipment.created'));
    }

    /**
     * Display an Equipment Type.
     */
    public function show(EquipmentType $equipment): Response
    {
        $this->authorize('viewAny', $equipment);

        return Inertia::render('Equipment/Show', [
            'equipment' => $equipment, // ->load(['Customer', 'TechTip']),
        ]);
    }

    /**
     * Show the form for editing the Equipment Type.
     */
    public function edit(Request $request, EquipmentType $equipment): Response
    {
        $this->authorize('update', $equipment);

        return Inertia::render('Equipment/Edit', [
            'equipment' => fn() => $equipment->load('DataFieldType'),
            'category-list' => fn() => CacheData::equipmentCategories(),
            'data-list' => fn() => CacheData::dataFieldTypes()->pluck('name'),
            'public-tips' => fn() => $request->user()
                ->features()
                ->active(PublicTechTipFeature::class),
        ]);
    }

    /**
     * Update the specified Equipment Type.
     */
    public function update(
        EquipmentTypeRequest $request,
        EquipmentType $equipment
    ): RedirectResponse {
        $this->svc
            ->updateEquipmentType($request->safe()->collect(), $equipment);

        return redirect(route('equipment.index'))
            ->with('success', __('equipment.updated'));
    }

    /**
     * Remove the specified Equipment.
     */
    public function destroy(EquipmentType $equipment): RedirectResponse
    {
        $this->authorize('delete', $equipment);

        $this->svc->destroyEquipmentType($equipment);

        return redirect(route('equipment.index'))
            ->with('warning', __('equipment.destroyed'));
    }
}
