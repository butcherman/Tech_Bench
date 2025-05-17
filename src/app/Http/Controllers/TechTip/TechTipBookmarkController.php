<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookmarkRequest;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class TechTipBookmarkController extends Controller
{
    /**
     * Turn on or off a Tech Tip Bookmark for user
     */
    public function __invoke(BookmarkRequest $request, TechTip $tech_tip): Response
    {
        if ($request->value) {
            $tech_tip->Bookmarks()->attach($request->user());
        } else {
            $tech_tip->Bookmarks()->detach($request->user());
        }

        return response(['success' => true]);
    }
}
