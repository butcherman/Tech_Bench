<?php

namespace App\Service\Misc;

use App\Models\PhoneNumberType;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class PhoneTypeService
{
    public function createPhoneType(Collection $requestData): PhoneNumberType
    {
        return PhoneNumberType::create($requestData->toArray());
    }

    public function updatePhoneType(
        Collection $requestData,
        PhoneNumberType $type
    ): PhoneNumberType {
        $type->update($requestData->toArray());

        return $type;
    }

    public function destroyPhoneType(PhoneNumberType $type): void
    {
        try {
            $type->delete();
        } catch (QueryException $e) {
            CheckDatabaseError::check(
                $e,
                'Unable to delete, Phone Number Type is in use by at least one customer'
            );
        }
    }
}
