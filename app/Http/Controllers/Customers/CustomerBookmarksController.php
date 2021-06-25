<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\UserCustomerBookmark;
use App\Http\Requests\Customers\CustomerBookmarkRequest;

use Illuminate\Support\Facades\Auth;

class CustomerBookmarksController extends Controller
{
    /**
     *  Add or remove a customer from the users Bookmarks
     */
    public function __invoke(CustomerBookmarkRequest $request)
    {
        if($request->state)
        {
            UserCustomerBookmark::create([
                'user_id' => Auth::user()->user_id,
                'cust_id' => $request->cust_id,
            ]);
        }
        else
        {
            UserCustomerBookmark::where('user_id', Auth::user()->user_id)->where('cust_id', $request->cust_id)->first()->delete();
        }

        return response()->noContent();
    }
}
