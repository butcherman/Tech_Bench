<?php

namespace App\Services\TechTip;

use App\Facades\DbException;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class TechTipCommentService
{
    /**
     * Create a new Tech Tip Comment.
     */
    public function createComment(
        Collection $requestData,
        TechTip $techTip,
        User $user
    ): TechTipComment {
        return $techTip->TechTipComment()->save(new TechTipComment([
            'user_id' => $user->user_id,
            'comment' => $requestData->get('comment_data'),
        ]));
    }

    /**
     * Update an existing Tech Tip Comment.
     */
    public function updateComment(
        Collection $requestData,
        TechTipComment $comment,
    ): TechTipComment {
        $comment->comment = $requestData->get('comment_data');
        $comment->save();

        return $comment->fresh();
    }

    /**
     * Delete a Tech Tip Comment.
     */
    public function destroyComment(TechTipComment $comment): void
    {
        $comment->delete();
    }

    /**
     * Flag a comment for review
     */
    public function flagComment(TechTipComment $comment, User $flaggedBy): void
    {
        try {
            $comment->flagComment($flaggedBy);
        } catch (QueryException $e) {
            DbException::check($e);
        }
    }

    /**
     * Remove a Flag from a Tech Tip Comment
     */
    public function removeCommentFlag(TechTipComment $comment): void
    {
        $flags = $comment->Flags;
        foreach ($flags as $flag) {
            $flag->delete();
        }
    }
}