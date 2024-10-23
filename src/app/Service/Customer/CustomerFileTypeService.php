<?php

namespace App\Service\Customer;

use App\Models\CustomerFileType;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class CustomerFileTypeService
{
    public function createFileType(Collection $requestData): CustomerFileType
    {
        return CustomerFileType::create($requestData->toArray());
    }

    public function updateFileType(
        Collection $requestData,
        CustomerFileType $file_type
    ): CustomerFileType {
        $file_type->update($requestData->toArray());

        return $file_type;
    }

    public function destroyFileType(CustomerFileType $file_type): void
    {
        try {
            $file_type->delete();

        } catch (QueryException $e) {
            CheckDatabaseError::check(
                $e,
                'Unable to delete, File Type is in use by at least one customer'
            );
        }
    }
}
