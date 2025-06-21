<?php

namespace Tests\Feature\Maintenance\Logs;

use App\Exceptions\Maintenance\InvalidLogChannelException;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
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
            ->assertInertia(fn (Assert $page) => $page
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

    public function test_invoke_with_invalid_channel(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $this->expectException(InvalidLogChannelException::class);

        $response = $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get(route('maint.logs.index', ['YourMom']));

        $response->assertStatus(404);

        Exceptions::assertReported(InvalidLogChannelException::class);
    }

    public function test_invoke_with_channel(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.index', ['Application']));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Maint/LogIndex')
                ->has('channels')
                ->has('channel')
                ->has('log-list'));
    }
}
