<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\SendTestEmail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendTestEmailUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | send()
    |---------------------------------------------------------------------------
    */
    public function test_send(): void
    {
        Mail::fake();

        $settings = [
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'test@example.com',
            'password' => 'secret',
            'from_address' => 'test@example.com',
        ];

        $build = [
            'transport' => 'smtp',
            'host' => $settings['host'],
            'port' => (int) $settings['port'],
            'encryption' => $settings['encryption'] ?: null,
            'username' => $settings['username'] ?: null,
            'password' => $settings['password'] ?: null,
            'timeout' => 10,
            'local_domain' => parse_url(
                config('app.url'),
                PHP_URL_HOST
            ),
        ];

        $recipient = 'recipient@example.com';

        Mail::shouldReceive('build')->once()->with($build)->andReturnSelf();
        Mail::shouldReceive('raw')->once();

        $testObj = new SendTestEmail;
        $testObj->send($settings, $recipient);
    }
}
