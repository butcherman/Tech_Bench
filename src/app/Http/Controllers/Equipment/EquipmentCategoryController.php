<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentService;
use Illuminate\Http\RedirectResponse;

class EquipmentCategoryController extends Controller
{
    public function __construct(protected EquipmentService $svc) {}

    /**
     * Store the newly created Equipment Category.
     */
    public function store(EquipmentCategoryRequest $request): RedirectResponse
    {
        $this->svc->createCategory($request->safe()->collect());

        return back()->with('success', __('equipment.category.created'));
    }

    /**
     * Update the Equipment Category.
     */
    public function update(
        EquipmentCategoryRequest $request,
        EquipmentCategory $equipment_category
    ): RedirectResponse {
        $this->svc->updateCategory(
            $request->safe()->collect(),
            $equipment_category
        );

        return back()->with('success', __('equipment.category.updated'));
    }

    /**
     * Remove the Equipment Category.
     */
    public function destroy(
        EquipmentCategory $equipment_category
    ): RedirectResponse {
        $this->authorize('delete', EquipmentType::class);

        $this->svc->destroyCategory($equipment_category);

        return back()->with('warning', __('equipment.category.destroyed'));
    }
}
