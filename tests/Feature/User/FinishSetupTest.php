<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class FinishSetupTest extends TestCase
{
    protected $password = 'ChangeMe$secure1';

    /**
     * Invoke Method
     */
    public function test_invoke_while_logged_in()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token'    => $token = Str::uuid(),
        ]);
        $data = [
            'password'              => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('finish-setup', $token), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_invoke_invalid_token()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token'    => Str::uuid(),
        ]);
        $token = Str::uuid();
        $data = [
            'password'              => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('finish-setup', $token), $data);
        $response->assertStatus(404);
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token'    => $token = Str::uuid(),
        ]);
        $data = [
            'password'              => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('finish-setup', $token), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', __('user.setup_completed'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseMissing('user_initializes', $link->toArray());
    }
}
