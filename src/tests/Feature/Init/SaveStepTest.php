<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Tests\TestCase;

class SaveStepTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'UTC',
            'max_filesize' => 123456,
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->put(route('init.step-1.submit'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_step_1(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::find(1);
        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'UTC',
            'max_filesize' => 123456,
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->actingAs($user)
            ->put(route('init.step-1.submit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas([
                'setup' => [
                    'application-settings' => $data,
                ],
            ]);
    }

    public function test_invoke_step_2(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::find(1);
        $data = [
            'from_address' => 'new@email.org',
            'username' => 'testName',
            'password' => 'blahBlah',
            'host' => 'randomHost.com',
            'port' => 25,
            'encryption' => 'none',
            'require_auth' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('init.step-2.submit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas([
                'setup' => [
                    'email-settings' => $data,
                ],
            ]);
    }

    public function test_invoke_step_3(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::find(1);
        $data = [
            'password' => [

                'expire' => '60',
                'min_length' => '12',
                'contains_uppercase' => false,
                'contains_lowercase' => false,
                'contains_number' => false,
                'contains_special' => false,
                'disable_compromised' => false,
            ],
            'twoFa' => [
                'enabled' => true,
                'required' => false,
                'allow_save_device' => true,
                'methods' => [
                    'email' => true,
                    'authenticator' => true,
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('init.step-3.submit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas([
                'setup' => [
                    'security' => $data,
                ],
            ]);
    }

    public function test_invoke_step_4(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $rules = [
            'password' => [

                'expire' => '60',
                'min_length' => '3',
                'contains_uppercase' => false,
                'contains_lowercase' => false,
                'contains_number' => false,
                'contains_special' => false,
                'disable_compromised' => false,
            ],
            'twoFa' => [
                'enabled' => true,
                'required' => false,
                'allow_save_device' => true,
                'methods' => [
                    'email' => true,
                    'authenticator' => true,
                ],
            ],
        ];

        /** @var User $user */
        $user = User::find(1);

        $data = [
            'username' => 'admin',
            'first_name' => 'Some',
            'last_name' => 'Dude',
            'email' => 'some.dude@noem.com',
            'role_id' => 1,
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->actingAs($user)
            ->withSession(['setup.security' => $rules])
            ->put(route('init.step-4.submit', 'admin'), $data);

        $response->assertStatus(302)
            ->assertSessionHas([
                'setup' => [
                    'admin' => $data,
                    'security' => $rules,
                ],
            ]);
    }
}
