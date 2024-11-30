<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookmarkRequest;
use App\Models\TechTip;
use Illuminate\Http\Response;

class TechTipBookmarkController extends Controller
{
    /**
     * Toggle a Tech Tip Bookmark for a User
     */
    public function __invoke(BookmarkRequest $request, TechTip $techTip): Response
    {
        $techTip->toggleBookmark($request->user(), $request->get('value'));

        return response(['success' => true]);
    }
}
