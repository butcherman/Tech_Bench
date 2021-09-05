<?php

namespace App\Http\Controllers\Customers;

use Exception;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\UserCustomerBookmark;
use App\Http\Requests\Customers\CustomerBookmarkRequest;

class CustomerBookmarkController extends Controller
{
    /**
     * Toggle a customer bookmark on and off
     */
    public function __invoke(CustomerBookmarkRequest $request)
    {
        if($request->state)
        {
            try
            {
                UserCustomerBookmark::create([
                    'user_id' => $request->user()->user_id,
                    'cust_id' => $request->cust_id,
                ]);
            }
            catch(Exception $e)
            {
                //  If for some reason the add fails, trigger error
                Log::critical('User '.$request->user()->username.' is trying to bookmark a customer that is already bookmarked', [
                    'cust_id' => $request->cust_id,
                    'state'   => $request->state,
                    'user_id' => $request->user()->user_id,
                ]);
                abort(409, 'Bookmark already exists');
            }
        }
        else
        {
            //  Remove the bookmark
            UserCustomerBookmark::where('user_id', $request->user()->user_id)->where('cust_id', $request->cust_id)->first()->delete();
        }

        return response()->noContent();
    }
}
