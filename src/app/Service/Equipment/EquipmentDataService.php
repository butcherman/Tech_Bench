<?php

namespace App\Service\Equipment;

use App\Models\DataFieldType;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class EquipmentDataService
{
    public function createDataType(Collection $requestData): DataFieldType
    {
        $newField = DataFieldType::create($requestData->toArray());

        return $newField;
    }

    public function updateDataType(Collection $requestData, DataFieldType $field): DataFieldType
    {
        $field->update($requestData->toArray());

        return $field;
    }

    public function destroyDataType(DataFieldType $field): void
    {
        try {
            $field->delete();
        } catch (QueryException $e) {
            (new CheckDatabaseError)->check(
                $e,
                $field->name.' is still in use and cannot be deleted'
            );
        }
    }
}
