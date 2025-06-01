<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StepFiveTest extends TestCase
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

        $response = $this->get(route('init.step-5'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::find(1);;

        $response = $this->actingAs($user)
            ->withSession([
                'setup' => [
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
                    'admin' => User::factory()->make(),
                ],
            ])
            ->get(route('init.step-5'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Init/StepFive')
            );
    }
}
