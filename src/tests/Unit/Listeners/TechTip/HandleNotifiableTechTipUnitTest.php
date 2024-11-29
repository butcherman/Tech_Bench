<?php

namespace Tests\Unit\Listeners\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Listeners\TechTip\HandleNotifiableTechTipListener;
use App\Mail\TechTip\NewTechTipMail;
use App\Mail\TechTip\UpdatedTechTipMail;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleNotifiableTechTipUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_create(): void
    {
        Mail::fake();

        $techTip = TechTip::factory()->create();
        $userList = User::factory()->count(5)->create();

        $event = new NotifiableTechTipEvent(
            $techTip,
            User::find($techTip->user_id),
            CrudAction::Create
        );

        $listener = new HandleNotifiableTechTipListener;
        $listener->handle($event);

        foreach ($userList as $user) {
            Mail::assertQueued(NewTechTipMail::class, $user->email);
        }
    }

    public function test_handle_update(): void
    {
        Mail::fake();

        $techTip = TechTip::factory()->create();
        $userList = User::factory()->count(5)->create();

        $event = new NotifiableTechTipEvent(
            $techTip,
            User::find($techTip->user_id),
            CrudAction::Update
        );

        $listener = new HandleNotifiableTechTipListener;
        $listener->handle($event);

        foreach ($userList as $user) {
            Mail::assertQueued(UpdatedTechTipMail::class, $user->email);
        }
    }
}
