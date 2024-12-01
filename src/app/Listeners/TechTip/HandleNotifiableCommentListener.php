<?php

namespace App\Listeners\TechTip;

use App\Events\TechTip\NotifiableCommentEvent;
use App\Facades\GetMailable;
use App\Mail\TechTip\NewCommentMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleNotifiableCommentListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(NotifiableCommentEvent $event): void
    {
        $userList = GetMailable::getAllMailable($event->comment->User);

        foreach ($userList as $user) {
            Mail::to($user)
                ->send(
                    new NewCommentMail($user, $event->comment->load('TechTip')
                    )
                );
        }
    }
}
