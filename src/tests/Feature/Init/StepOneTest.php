<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StepOneTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->get(route('init.step-1'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('init.step-1'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Init/StepOne')
                ->has('step')
                ->has('settings')
                ->has('timezone-list')
            );
    }

    public function test_invoke_with_session_data()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->withSession([
                'setup' => [
                    'basic-settings' => [
                        'url' => 'https://someUrl.noSite',
                        'timezone' => 'UTC',
                        'max_filesize' => 123456,
                        'company_name' => 'Bobs Fancy Cats',
                    ],
                ],
            ])
            ->get(route('init.step-1'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Init/StepOne')
                ->has('step')
                ->has('settings')
                ->has('timezone-list')
            );
    }
}
