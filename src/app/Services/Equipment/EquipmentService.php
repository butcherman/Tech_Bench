<?php

namespace App\Services\Equipment;

use App\Facades\DbException;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class EquipmentService
{
    public function createCategory(Collection $requestData): EquipmentCategory
    {
        $newCat = EquipmentCategory::create($requestData->toArray());

        return $newCat;
    }

    public function updateCategory(
        Collection $requestData,
        EquipmentCategory $category
    ): EquipmentCategory {
        $category->update($requestData->toArray());

        return $category;
    }

    public function destroyCategory(EquipmentCategory $category): void
    {
        try {
            $category->delete();
        } catch (QueryException $e) {
            DbException::check(
                $e,
                __('equipment.category.in-use', ['name' => $category->name])
            );
        }
    }

    public function createEquipmentType(Collection $requestData): EquipmentType
    {
        $newEquipment = EquipmentType::create($requestData->only([
            'cat_id',
            'name',
            'allow_public_tip',
        ])->toArray());

        $this->processDataFields($newEquipment, $requestData->get('custData'));

        return $newEquipment;
    }

    public function updateEquipmentType(
        Collection $requestData,
        EquipmentType $equipment
    ): EquipmentType {
        $equipment->update(
            $requestData->only(['cat_id', 'name', 'allow_public_tip'])
                ->toArray()
        );

        $this->processDataFields($equipment, $requestData->get('custData'));

        // TODO - Create Job
        // UpdateCustomerDataFieldsJob::dispatch($equipment);

        return $equipment;
    }

    public function destroyEquipmentType(EquipmentType $equipment): void
    {
        try {
            $equipment->delete();
        } catch (QueryException $e) {
            DbException::check(
                $e,
                __('equipment.in_use', ['name' => $equipment->name])
            );
        }
    }

    /**
     * Process the Customer Equipment Entries, add or remove any necessary
     */
    protected function processDataFields(
        EquipmentType $equipment,
        array $fieldList
    ): void {
        // Make field list a collection for easier processing
        $fieldList = collect($fieldList);

        // Cycle through all submitted fields to determine if any need to be created
        $equipmentFields = $this->buildDataFieldIdList($fieldList);
        $dataToSync = [];

        // Add Order property to Equipment Fields
        $order = 0;
        foreach ($equipmentFields as $fieldId) {
            $dataToSync[$fieldId] = ['order' => $order];
            $order++;
        }

        $equipment->DataFieldType()->sync($dataToSync);
    }

    /**
     * Create a list of Filed ID's that belong to this equipment
     */
    protected function buildDataFieldIdList(Collection $fieldList): Collection
    {
        return $fieldList->map(function ($field) {
            // Determine if the field is a valid field already
            $valid = DataFieldType::where('name', $field)->first();

            // If not, create it
            if (! $valid) {
                $valid = DataFieldType::create(['name' => $field]);
            }

            return $valid->type_id;
        });
    }
}
