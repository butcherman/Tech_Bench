<?php

namespace App\Services\TechTip;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
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
}
