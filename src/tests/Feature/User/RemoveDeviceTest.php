<?php

namespace Tests\Feature\User;

use App\Models\DeviceToken;
use App\Models\User;
use Tests\TestCase;

class RemoveDeviceTest extends TestCase
{
    protected $testAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function test_invoke_guest()
    {
        $token = DeviceToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->get(route('user.remove-device', [
            $user->username,
            $token->device_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $token = DeviceToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('user.remove-device', [$user->username, $token->device_id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.device-removed'));

        $this->assertDatabaseMissing('device_tokens', ['device_id' => $token->device_id]);
    }

    public function test_invoke_as_admin()
    {
        $token = DeviceToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('user.remove-device', [$user->username, $token->device_id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.device-removed'));

        $this->assertDatabaseMissing('device_tokens', ['device_id' => $token->device_id]);
    }

    public function test_invoke_another_user()
    {
        $token = DeviceToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('user.remove-device', [$user->username, $token->device_id]));
        $response->assertStatus(403);
    }

    public function test_invoke_higher_user()
    {
        $token = DeviceToken::factory()->create();
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))
            ->get(route('user.remove-device', [$user->username, $token->device_id]));
        $response->assertStatus(403);
    }
}
