<?php

namespace Tests\Unit\Listeners\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Models\TechTip;
use App\Models\User;
use App\Notifications\TechTip\NewTechTipNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class HandleNotifiableTechTipListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_new_tech_tip(): void
    {
        Notification::fake();

        User::factory()->count(10)->create();

        $techTip = TechTip::factory()->create();
        $user = User::find($techTip->user_id);
        $action = CrudAction::Create;

        NotifiableTechTipEvent::dispatch($techTip, $user, $action);

        Notification::assertSentTimes(
            NewTechTipNotification::class,
            User::all()->count() - 1
        );

        Notification::assertNotSentTo($user, NewTechTipNotification::class);
    }
}
