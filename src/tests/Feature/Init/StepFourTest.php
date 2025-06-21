<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StepFourTest extends TestCase
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

        $response = $this->get(route('init.step-4'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get(route('init.step-4'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Init/StepFour')
                    ->has('step')
                    ->has('rules')
                    ->has('roles')
                    ->has('user')
                    ->has('has-pass')
            );
    }

    public function test_invoke_with_session_data(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->withSession([
                'setup' => [
                    'administrator-account' => User::factory()->make(),
                ],
            ])
            ->get(route('init.step-4'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Init/StepFour')
            );
    }
}
