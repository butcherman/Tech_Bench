<?php

namespace App\Traits;

use App\Facades\CacheData;
use App\Models\PhoneNumberType;

trait PhoneNumberTrait
{
    /**
     * Return object of the phone number type
     */
    protected function getPhoneNumberType(string $typeName): PhoneNumberType
    {
        // return PhoneNumberType::where('description', $typeName)->first();
        return CacheData::phoneTypes()->where('description', $typeName)->first();
    }

    /**
     * Remove all formatting from a number, end result should be a 10 digit string
     */
    protected function cleanPhoneString(string $number): string
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
