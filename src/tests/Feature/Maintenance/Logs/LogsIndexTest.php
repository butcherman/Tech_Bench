<?php

namespace Tests\Feature\Maintenance\Logs;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LogsIndexTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('maint.logs.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.index'));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.index'));

        $response->assertSuccessful()
            ->assertInertia(fn(Assert $page) => $page
                ->component('Maint/LogIndex')
                ->has('channels')
                ->has('channel')
                ->has('log-list'));
    }

    public function test_invoke_guest_with_channel(): void
    {
        $response = $this->get(route('maint.logs.index', ['User']));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission_with_channel(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.index', ['User']));

        $response->assertStatus(403);
    }

    // TODO - Validate Log Channel
    // public function test_invoke_with_invalid_channel(): void
    // {
    //     /** @var User $user */
    //     $user = User::factory()->createQuietly(['role_id' => 1]);

    //     $response = $this->actingAs($user)
    //         ->get(route('maint.logs.index', ['YourMom']));

    //     $response->assertStatus(404);
    // }

    public function test_invoke_with_channel(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.index', ['Application']));

        $response->assertSuccessful()
            ->assertInertia(fn(Assert $page) => $page
                ->component('Maint/LogIndex')
                ->has('channels')
                ->has('channel')
                ->has('log-list'));
    }
}
