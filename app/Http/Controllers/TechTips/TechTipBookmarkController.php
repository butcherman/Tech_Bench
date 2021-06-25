<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipBookmarkRequest;
use App\Models\TechTipBookmark;
use Illuminate\Http\Request;

class TechTipBookmarkController extends Controller
{
    /**
     *  Add or remove a Tech Tip from the users Bookmarks
     */
    public function __invoke(TechTipBookmarkRequest $request)
    {
        if($request->state)
        {
            TechTipBookmark::create([
                'user_id' => $request->user()->user_id,
                'tip_id'  => $request->tip_id,
            ]);
        }
        else
        {
            TechTipBookmark::where('user_id', $request->user()->user_id)->where('tip_id', $request->tip_id)->first()->delete();
        }

        return response()->noContent();
    }
}
