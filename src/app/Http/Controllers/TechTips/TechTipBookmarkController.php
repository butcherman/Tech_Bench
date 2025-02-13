<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\BookmarkRequest;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TechTipBookmarkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BookmarkRequest $request, TechTip $techTip): Response
    {
        if ($request->value) {
            $techTip->Bookmarks()->attach($request->user());
        } else {
            $techTip->Bookmarks()->detach($request->user());
        }

        return response(['success' => true]);
    }
}
