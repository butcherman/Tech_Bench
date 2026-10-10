<?php

namespace Tests\Unit\Actions\Init;

use App\Actions\Init\BuildApplication;
use App\Events\Admin\AdministrationEvent;
use App\Jobs\User\UpdatePasswordExpireJob;
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
            'application-settings' => [
                'url' => 'https://someUrl.noSite',
                'timezone' => 'UTC',
                'max_filesize' => 123456,
                'company_name' => 'Bobs Fancy Cats',
                'home_links' => [[], [], []],
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
            'security' => [
                'password' => [
                    'expire' => '60',
                    'min_length' => '12',
                    'contains_uppercase' => 'false',
                    'contains_lowercase' => 'false',
                    'contains_number' => 'false',
                    'contains_special' => 'false',
                    'disable_compromised' => 'false',
                ],
                'twoFa' => [
                    'enables' => true,
                    'required' => false,
                    'allow_save_device' => true,
                    'methods' => [
                        'email' => true,
                        'authenticator' => true,
                    ],
                ],

            ],
            'admin' => [
                'email' => 'admin@em.fake',
                'first_name' => 'Some',
                'last_name' => 'Dude',
                'role_id' => 1,
                'password' => 'MyCoolPassword!!',
                'password_confirmation' => 'MyCoolPassword!!',
            ],
        ];

        $obj = new BuildApplication;
        $obj($data);

        Event::assertDispatchedTimes(AdministrationEvent::class, 6);
        Queue::assertPushed(UpdatePasswordExpireJob::class);
    }
}
