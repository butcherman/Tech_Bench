<?php

namespace Tests\Feature\Admin\User;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ResetTwoFaTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config(['auth.twoFa.required' => true]);

        $user = User::factory()->create();
        $user->two_factor_secret = Str::uuid();
        $user->two_factor_recovery_codes = Str::uuid();
        $user->two_factor_confirmed_at = now();
        $user->two_factor_via = 'authenticator';

        DeviceToken::factory()->count(5)->create(['user_id' => $user->user_id]);

        $response = $this->put(route('admin.user.two-factor.reset', $user->username));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        config(['auth.twoFa.required' => true]);

        /** @var User $admin */
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $user->two_factor_secret = Str::uuid();
        $user->two_factor_recovery_codes = Str::uuid();
        $user->two_factor_confirmed_at = now();
        $user->two_factor_via = 'authenticator';

        DeviceToken::factory()->count(5)->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($admin)
            ->put(route('admin.user.two-factor.reset', $user->username));

        $response->assertForbidden();
    }

    public function test_invoke_feature_disabled(): void
    {
        config(['auth.twoFa.required' => false]);

        /** @var User $admin */
        $admin = User::factory()->create(['role_id' => 1]);
        $user = User::factory()->create();
        $user->two_factor_secret = Str::uuid();
        $user->two_factor_recovery_codes = Str::uuid();
        $user->two_factor_confirmed_at = now();
        $user->two_factor_via = 'authenticator';

        DeviceToken::factory()->count(5)->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($admin)
            ->put(route('admin.user.two-factor.reset', $user->username));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        config(['auth.twoFa.required' => true]);

        /** @var User $admin */
        $admin = User::factory()->create(['role_id' => 1]);
        $user = User::factory()->create();
        $user->two_factor_secret = Str::uuid();
        $user->two_factor_recovery_codes = Str::uuid();
        $user->two_factor_confirmed_at = now();
        $user->two_factor_via = 'authenticator';

        DeviceToken::factory()->count(5)->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($admin)
            ->put(route('admin.user.two-factor.reset', $user->username));

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Two Factor Settings Reset');

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_via' => null,
        ]);

        $this->assertDatabaseMissing('device_tokens', [
            'user_id' => $user->user_id,
        ]);
    }
}
