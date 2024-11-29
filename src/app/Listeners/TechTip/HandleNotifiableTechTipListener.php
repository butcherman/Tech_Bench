<?php

namespace App\Listeners\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Facades\GetMailable;
use App\Mail\TechTip\NewTechTipMail;
use App\Mail\TechTip\UpdatedTechTipMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleNotifiableTechTipListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(NotifiableTechTipEvent $event): void
    {
        $userList = GetMailable::getAllMailable($event->user);

        foreach ($userList as $user) {
            if ($event->action === CrudAction::Create) {
                Mail::to($user)
                    ->send(new NewTechTipMail($user, $event->techTip));
            }

            if ($event->action === CrudAction::Update) {
                mail::to($user)
                    ->send(new UpdatedTechTipMail($user, $event->techTip));
            }
        }
    }
}
