<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\SendTestEmail;
use App\Mail\Admin\TestEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class SendTestEmailUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $testObj = new SendTestEmail;
        $res = $testObj($user);

        $this->assertEquals([true, __('admin.email.test')], $res);

        Mail::assertSent(TestEmail::class);
    }

    public function test_invoke_fail(): void
    {
        $user = User::factory()->create();

        Mail::shouldReceive('to')
            ->once()
            ->with($user)
            ->andThrow(new TransportException('Test Exception'));

        $testObj = new SendTestEmail;
        $res = $testObj($user);

        $this->assertEquals([false, 'Test Exception'], $res);
    }
}
