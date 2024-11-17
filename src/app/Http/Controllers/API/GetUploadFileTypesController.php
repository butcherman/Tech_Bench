<?php

namespace App\Http\Controllers\API;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GetUploadFileTypesController extends Controller
{
    /**
     * Get a list of available File Types for Uploaded Files
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(CacheFacade::fileTypes());
    }
}
