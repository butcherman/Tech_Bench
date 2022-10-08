<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class InitializeUserTest extends TestCase
{
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

        $response = $this->actingAs(User::factory()->create())->get(route('initialize', $token));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_invoke_invalid_token()
    {
        $user  = User::factory()->create();
        $token = Str::uuid();
        $link  = UserInitialize::create([
            'username' => $user->username,
            'token'    => Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));
        $response->assertStatus(404);
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token'    => $token = Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));
        $response->assertSuccessful();
    }
}
