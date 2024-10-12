<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\BookmarkRequest;
use App\Models\Customer;
use Illuminate\Http\Response;

class CustomerBookmarkController extends Controller
{
    /**
     * Turn on or off a Customer Bookmark for a user
     */
    public function __invoke(BookmarkRequest $request, Customer $customer): Response
    {
        if ($request->value) {
            $customer->Bookmarks()->attach($request->user());
        } else {
            $customer->Bookmarks()->detach($request->user());
        }

        return response(['success' => true]);
    }
}
