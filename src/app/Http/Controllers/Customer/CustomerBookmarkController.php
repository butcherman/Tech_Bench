<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookmarkRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerBookmarkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BookmarkRequest $request, Customer $customer): Response
    {
        $customer->toggleBookmark($request->user(), $request->get('value'));

        return response(['success' => true]);
    }
}
