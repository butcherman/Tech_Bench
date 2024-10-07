<?php

namespace Tests\Feature\Admin\User;

use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class SendWelcomeEmailTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Bus::fake();

        $user = User::factory()->create();

        $response = $this->get(route('admin.user.send-welcome', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Bus::assertNothingDispatched();
    }

    public function test_invoke_no_permission()
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $newUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.user.send-welcome', $newUser->username));
        $response->assertStatus(403);

        Bus::assertNothingDispatched();
    }

    public function test_invoke()
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $newUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.user.send-welcome', $newUser->username));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.welcome_sent'));

        Bus::assertDispatched(SendWelcomeEmailJob::class);
    }
}
