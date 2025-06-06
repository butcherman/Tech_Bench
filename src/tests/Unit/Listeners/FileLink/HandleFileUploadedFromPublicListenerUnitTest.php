<?php

namespace Tests\Unit\Listeners\FileLink;

use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use App\Models\User;
use App\Notifications\FileLink\LinkFileUploadedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class HandleFileUploadedFromPublicListenerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'bob',
        ]);

        FileUploadedFromPublicEvent::dispatch($link, $timeline->timeline_id);

        Notification::assertSentTo($user, LinkFileUploadedNotification::class);
    }
}
