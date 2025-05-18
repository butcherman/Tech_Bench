<?php

namespace App\Services\TechTip;

use App\Events\TechTip\NotifiableTipCommentEvent;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Support\Collection;

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
}
