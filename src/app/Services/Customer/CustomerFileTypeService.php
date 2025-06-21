<?php

namespace App\Services\Customer;

use App\Facades\DbException;
use App\Models\CustomerFileType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class CustomerFileTypeService
{
    /**
     * Create a new Customer File Type
     */
    public function createFileType(Collection $requestData): CustomerFileType
    {
        return CustomerFileType::create($requestData->toArray());
    }

    /**
     * Update the name of a Customer File Type
     */
    public function updateFileType(Collection $requestData, CustomerFileType $file_type): CustomerFileType
    {
        $file_type->update($requestData->toArray());

        return $file_type->fresh();
    }

    /**
     * Try to delete a Customer File type.  This operation will fail if the
     * file type is in use by any customer.
     */
    public function destroyFileType(CustomerFileType $file_type): void
    {
        try {
            $file_type->delete();
        } catch (QueryException $e) {
            DbException::check(
                $e,
                'Unable to delete, File Type is in use by at least one customer'
            );
        }
    }
}
