<?php

namespace App\Services\Misc;

use App\Facades\CacheFacade;
use App\Facades\DbException;
use App\Models\PhoneNumberType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class PhoneTypeService
{
    public function createPhoneType(Collection $requestData): PhoneNumberType
    {
        CacheFacade::clearCache('phoneTypes');
        return PhoneNumberType::create($requestData->toArray());
    }

    public function updatePhoneType(
        Collection $requestData,
        PhoneNumberType $type
    ): PhoneNumberType {
        $type->update($requestData->toArray());
        CacheFacade::clearCache('phoneTypes');

        return $type->fresh();
    }

    public function destroyPhoneType(PhoneNumberType $type): void
    {
        try {
            $type->delete();
            CacheFacade::clearCache('phoneTypes');
        } catch (QueryException $e) {
            DbException::check(
                $e,
                'Unable to delete, Phone Number Type is in use'
            );
        }
    }
}
