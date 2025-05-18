<?php

namespace App\Listeners\TechTip;

use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Mail\TechTip\TechTipCommentFlaggedMail;
use App\Traits\AllowTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class HandleTechTipCommentFlaggedListener implements ShouldQueue
{
    use AllowTrait;

    /**
     * Send Email to Administrators notifying them of flagged comment
     */
    public function handle(TechTipCommentFlaggedEvent $event): void
    {
        $userList = $this->getUsersWithPermission('App Settings');

        foreach ($userList as $user) {
            Mail::to($user)
                ->send(new TechTipCommentFlaggedMail($user, $event->comment));
        }
    }
}
