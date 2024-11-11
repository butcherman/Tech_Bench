<?php

namespace App\Services\Misc;

use App\Models\PhoneNumberType;

class PhoneNumberService
{
    /**
     * Return object of the phone number type
     */
    public function getPhoneNumberType(string $typeName): PhoneNumberType
    {
        return PhoneNumberType::where('description', $typeName)->first();
    }

    /**
     * Remove all formatting from a number, end result should be a 10 digit string
     */
    public function cleanPhoneString(string $number): string
    {
        return preg_replace(
            '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
            '$1$2$3',
            $number
        );
    }
}
