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

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('init.step-3'));
        $response->assertSuccessful();
    }

    public function test_invoke_with_session_data()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->withSession(['setup' => [
                'user-settings' => [
                    'expire' => '60',
                    'min_length' => '12',
                    'contains_uppercase' => 'false',
                    'contains_lowercase' => 'false',
                    'contains_number' => 'false',
                    'contains_special' => 'false',
                    'disable_compromised' => 'false',
                ],
            ]])
            ->get(route('init.step-3'));
        $response->assertSuccessful();
    }
}
