<?php

namespace App\Service\Equipment;

use App\Http\Requests\Equipment\EquipmentCategoryRequest;
use App\Http\Requests\Equipment\EquipmentTypeRequest;
use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Service\Cache;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class EquipmentService
{
    public function createCategory(EquipmentCategoryRequest $requestData): EquipmentCategory
    {
        $newCat = EquipmentCategory::create($requestData->all());

        return $newCat;
    }

    public function updateCategory(
        EquipmentCategoryRequest $requestData,
        EquipmentCategory $category
    ): EquipmentCategory {
        $category->update($requestData->all());

        return $category;
    }

    public function destroyCategory(EquipmentCategory $category): void
    {
        try {
            $category->delete();
            Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        } catch (QueryException $e) {
            (new CheckDatabaseError)
                ->check(
                    $e,
                    __('equipment.category.in-use', ['name' => $category->name])
                );
        }
    }

    public function createEquipmentType(EquipmentTypeRequest $requestData): EquipmentType
    {
        $newEquipment = EquipmentType::create($requestData->only([
            'cat_id',
            'name',
            'allow_public_tip',
        ]));

        $this->processDataFields($newEquipment, $requestData->custData);

        return $newEquipment;
    }

    public function updateEquipmentType(
        EquipmentTypeRequest $requestData,
        EquipmentType $equipment
    ): EquipmentType {
        $equipment->update($requestData->only(['cat_id', 'name', 'allow_public_tip']));
        $this->processDataFields($equipment, $requestData->custData);

        UpdateCustomerDataFieldsJob::dispatch($equipment);

        return $equipment;
    }

    public function destroyEquipmentType(EquipmentType $equipment): void
    {
        try {
            $equipment->delete();
        } catch (QueryException $e) {
            (new CheckDatabaseError)
                ->check(
                    $e,
                    __('equipment.in_use', ['name' => $equipment->name])
                );
        }
    }

    /**
     * Process the Customer Equipment Entries, add or remove any necessary
     */
    protected function processDataFields(EquipmentType $equipment, array $fieldList)
    {
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
    protected function buildDataFieldIdList(Collection $fieldList)
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
