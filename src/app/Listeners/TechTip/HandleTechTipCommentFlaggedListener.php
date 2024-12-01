<?php

namespace App\Listeners\TechTip;

use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Facades\GetMailable;
use App\Mail\TechTip\TechTipCommentFlaggedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleTechTipCommentFlaggedListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TechTipCommentFlaggedEvent $event): void
    {
        $userList = GetMailable::getAdminUsers();

        foreach ($userList as $user) {
            Mail::to($user)
                ->send(new TechTipCommentFlaggedMail($user, $event->comment));
        }
    }
}
