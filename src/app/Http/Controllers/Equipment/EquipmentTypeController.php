<?php

namespace App\Http\Controllers\Equipment;

use App\Features\PublicTechTipFeature;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Service\Cache;
use App\Service\Equipment\EquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentTypeController extends Controller
{
    public function __construct(protected EquipmentService $svc) {}

    /**
     * Display a listing of all Equipment Types.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Index', [
            'equipment-list' => fn () => Cache::equipmentCategories(),
        ]);
    }

    /**
     * Show the form for creating a new Equipment Type.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Create', [
            'category-list' => fn () => Cache::equipmentCategories(),
            'data-list' => fn () => DataFieldType::all()->pluck('name'),
            'public-tips' => fn () => $request->user()
                ->features()
                ->active(PublicTechTipFeature::class),
        ]);
    }

    /**
     * Store a newly created Equipment Type.
     */
    public function store(EquipmentTypeRequest $request): RedirectResponse
    {
        $this->svc->createEquipmentType($request);

        return redirect(route('equipment.index'))
            ->with('success', __('equipment.created'));
    }

    /**
     * Display the Equipment Type references where it is used.
     */
    public function show(EquipmentType $equipment): Response
    {
        $this->authorize('viewAny', $equipment);

        return Inertia::render('Equipment/Show', [
            'equipment' => $equipment->load(['Customer', 'TechTip']),
        ]);
    }

    /**
     * Show the form for editing the Equipment Type.
     */
    public function edit(Request $request, EquipmentType $equipment): Response
    {
        $this->authorize('update', $equipment);

        return Inertia::render('Equipment/Edit', [
            'equipment' => fn () => $equipment->load('DataFieldType'),
            'category-list' => fn () => Cache::equipmentCategories(),
            'data-list' => fn () => DataFieldType::all()->pluck('name'),
            'public-tips' => fn () => $request->user()
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
        $this->svc->updateEquipmentType($request, $equipment);

        return redirect(route('equipment.index'))
            ->with('success', __('equipment.updated'));
    }

    /**
     * Remove the Equipment Type.
     */
    public function destroy(EquipmentType $equipment): RedirectResponse
    {
        $this->authorize('delete', $equipment);

        $this->svc->destroyEquipmentType($equipment);

        return redirect(route('equipment.index'))
            ->with('warning', __('equipment.destroyed'));
    }
}
