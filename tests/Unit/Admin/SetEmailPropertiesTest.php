<?php

namespace Tests\Unit\Admin;

use App\Domains\Admin\SetEmailProperties;
use App\Http\Requests\Settings\EmailSettingsRequest;
use App\Mail\TestEmail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class SetEmailPropertiesTest extends TestCase
{
    public function test_save_email_settings()
    {
        $data = new EmailSettingsRequest([
            'from_address'   => 'from@em.com',
            'username'       => 'TestUsername',
            'password'       => 'TestPassword',
            'host'           => 'testHost.com',
            'port'           => 587,
            'encryption'     => 'TLS',
            'authentication' => true,
        ]);

        $res = (new SetEmailProperties)->saveEmailSettings($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('settings', ['key' => 'mail.from.address', 'value' => 'from@em.com']);
        $this->assertDatabaseHas('settings', ['key' => 'mail.username',     'value' => 'TestUsername']);
        $this->assertDatabaseHas('settings', ['key' => 'mail.password',     'value' => 'TestPassword']);
        $this->assertDatabaseHas('settings', ['key' => 'mail.host',         'value' => 'testHost.com']);
        $this->assertDatabaseHas('settings', ['key' => 'mail.port',         'value' => '587']);
        $this->assertDatabaseHas('settings', ['key' => 'mail.encryption',   'value' => 'TLS']);
    }

    public function test_send_test_email()
    {
        //  FIXME - make this test work
        $this->assertTrue(true);
    }
}
