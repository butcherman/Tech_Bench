<?php

namespace Tests\Unit\Actions\Init;

use App\Actions\Init\BuildApplication;
use App\Events\Admin\AdministrationEvent;
use App\Jobs\User\UpdatePasswordExpireJob;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildApplicationUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Event::fake();
        Queue::fake();

        $data = [
            'basic-settings' => [
                'url' => 'https://someUrl.noSite',
                'timezone' => 'UTC',
                'max_filesize' => 123456,
                'company_name' => 'Bobs Fancy Cats',
            ],
            'email-settings' => [
                'from_address' => 'new@email.org',
                'username' => 'testName',
                'password' => 'blahBlah',
                'host' => 'randomHost.com',
                'port' => 25,
                'encryption' => 'none',
                'require_auth' => true,
            ],
            'user-settings' => [
                'expire' => '60',
                'min_length' => '12',
                'contains_uppercase' => 'false',
                'contains_lowercase' => 'false',
                'contains_number' => 'false',
                'contains_special' => 'false',
                'disable_compromised' => 'false',
            ],
            'admin' => User::factory()->make()->makeVisible('role_id')->toArray(),
            'administrator-password' => [
                'current_password' => 'password',
                'password' => 'SomeN3wP@ssword',
                'password_confirmation' => 'SomeN3wP@ssword',
            ],
        ];

        $obj = new BuildApplication;
        $obj($data);

        Event::assertDispatchedTimes(AdministrationEvent::class, 6);
        Queue::assertPushed(UpdatePasswordExpireJob::class);
    }
}
