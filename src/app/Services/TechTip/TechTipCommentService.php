<?php

namespace App\Services\TechTip;

use App\Events\TechTip\NotifiableTipCommentEvent;
use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Facades\DbException;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TechTipCommentService
{
    /**
     * Store a new Tech Tip Comment.
     */
    public function createComment(Collection $requestData, TechTip $techTip, User $user): TechTipComment
    {
        $newComment = new TechTipComment([
            'comment' => $requestData->get('comment_data'),
            'user_id' => $user->user_id,
        ]);

        $techTip->Comments()->save($newComment);

        NotifiableTipCommentEvent::dispatch($newComment);

        return $newComment;
    }

    /**
     * Update an existing Tech Tip Comment.
     */
    public function updateComment(Collection $requestData, TechTipComment $comment): TechTipComment
    {
        $comment->comment = $requestData->get('comment_data');
        $comment->save();

        return $comment->refresh();
    }

    /**
     * Delete a Tech Tip Comment
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

            TechTipCommentFlaggedEvent::dispatch($comment, $flaggedBy);
        } catch (UniqueConstraintViolationException $e) {
            report($e);
            Log::notice(
                $flaggedBy->full_name .
                    ' is trying to flag a Tech Tip Comment that has already been flagged',
                $comment->toArray()
            );
        }
    }

    /**
     * Release a flagged comment.
     */
    public function releaseComment(TechTipComment $comment): void
    {
        $comment->Flags()->delete();
    }
}
