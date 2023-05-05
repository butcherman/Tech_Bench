<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\PhoneNumberType;
use Illuminate\Http\Request;

class GetPhoneTypesController extends Controller
{
    /**
     * Return the types of phone numbers that can be assigned to a contact
     */
    public function __invoke(Request $request)
    {
        return PhoneNumberType::all();
    }
}
