<?php

namespace Tests\Feature\Init;

use App\Models\User;
use Tests\TestCase;

class StepThreeTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->get(route('init.step-3'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('init.step-3'));
        $response->assertSuccessful();
    }
}
