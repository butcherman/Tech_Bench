<?php

namespace Tests\Feature\User;

use App\Models\DeviceToken;
use App\Models\User;
use Tests\TestCase;

class RemoveDeviceTokenTest extends TestCase
{
    // protected $testAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function test_invoke_guest(): void
    {
        $user = User::factory()->createQuietly();
        $token = DeviceToken::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->delete(route('user.remove-device', [
            $user->username,
            $token->device_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $token = DeviceToken::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('user.remove-device', [
                $user->username,
                $token->device_id,
            ]));

        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.device-removed'));

        $this->assertDatabaseMissing('device_tokens', [
            'device_id' => $token->device_id,
        ]);
    }

    public function test_invoke_as_admin(): void
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $token = DeviceToken::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($actingAs)
            ->delete(route('user.remove-device', [
                $user->username,
                $token->device_id,
            ]));

        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.device-removed'));

        $this->assertDatabaseMissing('device_tokens', [
            'device_id' => $token->device_id,
        ]);
    }

    public function test_invoke_another_user(): void
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $token = DeviceToken::factory()->createQuietly();

        $response = $this->actingAs($actingAs)
            ->delete(route('user.remove-device', [
                $user->username,
                $token->device_id,
            ]));

        $response->assertForbidden();
    }

    public function test_invoke_higher_user(): void
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 2]);
        $token = DeviceToken::factory()->createQuietly();
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($actingAs)
            ->delete(route('user.remove-device', [
                $user->username,
                $token->device_id,
            ]));

        $response->assertForbidden();
    }
}
