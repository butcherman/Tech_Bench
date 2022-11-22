<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerBookmarkRequest;
use Illuminate\Http\Request;

class CustomerBookmarkController extends Controller
{
    /**
     * Toggle a users bookmark to active or inactive
     */
    public function __invoke(CustomerBookmarkRequest $request)
    {
        $request->toggleBookmark();
        return response()->noContent();
    }
}
