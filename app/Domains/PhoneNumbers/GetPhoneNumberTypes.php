<?php

namespace App\Domains\PhoneNumbers;

use App\PhoneNumberTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetPhoneNumberTypes
{
    public function execute()
    {
        return PhoneNumberTypes::all();
    }
}
