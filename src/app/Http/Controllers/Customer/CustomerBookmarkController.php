<?php

// TODO - Refactor

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\BookmarkRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerBookmarkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BookmarkRequest $request, Customer $customer)
    {
        if ($request->value) {
            $customer->Bookmarks()->attach($request->user());
        } else {
            $customer->Bookmarks()->detach($request->user());
        }

        return response(['success' => true]);
    }
}
