<?php

namespace Tests\Unit\Listeners\FileLink;

use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Listeners\FileLink\HandleFileUploadedFromPublicListener;
use App\Mail\FileLink\FileLinkFileUploadedMail;
use App\Models\FileLink;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleFileUploadFromPublicUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $event = new FileUploadedFromPublicEvent($link);

        $listener = new HandleFileUploadedFromPublicListener;
        $listener->handle($event);

        Mail::assertQueued(FileLinkFileUploadedMail::class, $user->email);
    }
}
