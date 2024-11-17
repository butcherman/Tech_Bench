<?php

namespace App\Http\Controllers\API;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GetPhoneTypesController extends Controller
{
    /**
     * Get a list of all Phone Types to assign to phone number
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(CacheFacade::phoneTypes());
    }
}
