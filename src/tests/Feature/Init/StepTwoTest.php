<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StepTwoTest extends TestCase
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

        $response = $this->get(route('init.step-2'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('init.step-2'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Init/StepTwo')
                    ->has('step')
                    ->has('settings')
            );
    }

    public function test_invoke_with_session_data(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->withSession(['setup' => [
                'email-settings' => [
                    'from_address' => 'new@email.org',
                    'username' => 'testName',
                    'password' => 'blahBlah',
                    'host' => 'randomHost.com',
                    'port' => 25,
                    'encryption' => 'none',
                    'require_aut' => true,
                ],
            ]])
            ->get(route('init.step-2'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Init/StepTwo')
                    ->has('step')
                    ->has('settings')
            );
    }
}
