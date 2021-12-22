<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TipBookmarkRequest;
use App\Models\UserTechTipBookmark;
use Exception;

class TechTipBookmarkController extends Controller
{
    /**
     * Toggle a Tech Tip bookmark on and off
     */
    public function __invoke(TipBookmarkRequest $request)
    {
        if($request->state)
        {
            try
            {
                UserTechTipBookmark::create([
                    'user_id' => $request->user()->user_id,
                    'tip_id'  => $request->tip_id,
                ]);
            }
            catch(Exception $e)
            {
                //  If for some reason the add fails, trigger error
                report('User '.$request->user()->username.' is trying to bookmark a Tech Tip that is already bookmarked');
                report($e);
                abort(409, 'Bookmark already exists');
            }
        }
        else
        {
            //  Remove the bookmark
            UserTechTipBookmark::where('user_id', $request->user()->user_id)->where('tip_id', $request->tip_id)->first()->delete();
        }

        return response()->noContent();
    }
}
