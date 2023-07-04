<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('2fa.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('2fa.index'));
        $response->assertSuccessful();
    }

    public function test_get_already_verified()
    {
        $response = $this->actingAs(User::factory()->create())->withSession(['2fa_verified' => true])->get(route('2fa.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        $data = [
            'code' => '1234',
            'remember' => false,
        ];

        $response = $this->post(route('2fa.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set()
    {
        $user = User::factory()->create();
        UserCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '1234',
            'remember' => false,
        ];

        $response = $this->actingAs($user)->post(route('2fa.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_set_bad_code()
    {
        $user = User::factory()->create();
        $code = UserCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '4321',
            'remember' => false,
        ];

        $response = $this->actingAs($user)->post(route('2fa.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('2fa');
    }

    public function test_set_with_remember_device()
    {
        $user = User::factory()->create();
        UserCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '1234',
            'remember' => true,
        ];

        $response = $this->actingAs($user)->post(route('2fa.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('device_tokens', ['user_id' => $user->user_id]);
    }
}
