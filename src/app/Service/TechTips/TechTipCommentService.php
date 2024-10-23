<?php

namespace App\Service\TechTips;

use App\Events\TechTips\TipCommentFlaggedEvent;
use App\Exceptions\TechTips\CommentFlaggedAlreadyException;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TechTipCommentService
{
    public function createComment(Collection $requestData, TechTip $techTip): TechTipComment
    {
        return $techTip->TechTipComment()->save(new TechTipComment([
            'user_id' => request()->user()->user_id,
            'comment' => $requestData->comment_data,
        ]));
    }

    public function updateComment()
    {
        //
    }

    public function destroyComment()
    {
        //
    }

    public function flagComment(TechTipComment $comment)
    {
        try {
            $comment->flagComment();

            Log::notice(
                'Tech Tip comment has been flagged by '.request()->user()->username,
                $comment->toArray()
            );

            event(new TipCommentFlaggedEvent($comment));
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [1062])) {
                throw new CommentFlaggedAlreadyException(request());
            } else {
                // @codeCoverageIgnoreStart
                CheckDatabaseError::check($e);
                // @codeCoverageIgnoreEnd
            }
        }
    }
}
