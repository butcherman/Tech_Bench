<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Tests\TestCase;

class SaveStepTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
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

    public function test_invoke_step_1()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'UTC',
            'max_filesize' => 123456,
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->actingAs($user)
            ->put(route('init.step-1.submit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(['setup' => [
                'basic-settings' => $data,
            ],
            ]);
    }

    public function test_invoke_step_2()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
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
            ->assertSessionHas(['setup' => [
                'email-settings' => $data,
            ],
            ]);
    }

    public function test_invoke_step_3()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'expire' => '60',
            'min_length' => '12',
            'contains_uppercase' => false,
            'contains_lowercase' => false,
            'contains_number' => false,
            'contains_special' => false,
            'disable_compromised' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('init.step-3.submit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(['setup' => [
                'user-settings' => $data,
            ],
            ]);
    }

    public function test_invoke_step_4()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = User::factory()->make()->makeVisible('role_id')->toArray();

        $response = $this->actingAs($user)
            ->put(route('init.step-4.submit', 'admin'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(['setup' => [
                'admin' => $data,
            ],
            ]);
    }

    public function test_invoke_step_4b()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'current_password' => 'password',
            'password' => 'SomeN3wP@ssword',
            'password_confirmation' => 'SomeN3wP@ssword',
        ];

        $response = $this->actingAs($user)
            ->withSession(['setup' => [
                'user-settings' => [
                    'expire' => '60',
                    'min_length' => '12',
                    'contains_uppercase' => 'false',
                    'contains_lowercase' => 'false',
                    'contains_number' => 'false',
                    'contains_special' => 'false',
                    'disable_compromised' => 'false',
                ],
            ]])
            ->put(route('init.step-4b.submit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas([
                'setup' => [
                    'administrator-password' => $data,
                    'user-settings' => [
                        'expire' => '60',
                        'min_length' => '12',
                        'contains_uppercase' => 'false',
                        'contains_lowercase' => 'false',
                        'contains_number' => 'false',
                        'contains_special' => 'false',
                        'disable_compromised' => 'false',
                    ],
                ],
            ]);
    }
}
