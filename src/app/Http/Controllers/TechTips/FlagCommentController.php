<?php

namespace App\Http\Controllers\TechTips;

use App\Events\TechTips\TipCommentFlaggedEvent;
use App\Exceptions\TechTips\CommentFlaggedAlreadyException;
use App\Http\Controllers\Controller;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlagCommentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TechTip $techTip, TechTipComment $comment)
    {
        try {
            $comment->flagComment();
            Log::notice(
                'Tech Tip comment has been flagged by '.$request->user()->username,
                $comment->toArray()
            );
            event(new TipCommentFlaggedEvent($comment));
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [1062])) {
                throw new CommentFlaggedAlreadyException($request);
            } else {
                // @codeCoverageIgnoreStart
                CheckDatabaseError::check($e);
                // @codeCoverageIgnoreEnd
            }
        }

        return back()->with('warning', __('tips.comment.flagged'));
    }
}
