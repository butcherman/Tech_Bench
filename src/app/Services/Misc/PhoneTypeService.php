<?php

namespace App\Services\Misc;

use App\Facades\DbException;
use App\Models\PhoneNumberType;
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

        return $type->fresh();
    }

    public function destroyPhoneType(PhoneNumberType $type): void
    {
        try {
            $type->delete();
        } catch (QueryException $e) {
            DbException::check(
                $e,
                'Unable to delete, Phone Number Type is in use'
            );
        }
    }
}