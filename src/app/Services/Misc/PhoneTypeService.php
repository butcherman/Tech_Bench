<?php

namespace App\Services\Misc;

use App\Facades\CacheData;
use App\Facades\DbException;
use App\Models\PhoneNumberType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class PhoneTypeService
{
    /**
     * Create a new phone type.
     */
    public function createPhoneType(Collection $requestData): PhoneNumberType
    {
        CacheData::clearCache('phoneTypes');

        return PhoneNumberType::create($requestData->toArray());
    }

    /**
     * Update an existing phone type
     */
    public function updatePhoneType(
        Collection $requestData,
        PhoneNumberType $type
    ): PhoneNumberType {
        $type->update($requestData->toArray());

        CacheData::clearCache('phoneTypes');

        return $type->fresh();
    }

    /**
     * Delete a phone type.  Process will fail if phone type is referenced
     * anywhere else in the database.
     */
    public function destroyPhoneType(PhoneNumberType $type): void
    {
        try {
            $type->delete();

            CacheData::clearCache('phoneTypes');
        } catch (QueryException $e) {
            DbException::check(
                $e,
                'Unable to delete, Phone Number Type is in use'
            );
        }
    }
}
