<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Tests\TestCase;

class GetPhoneTypesTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('get-number-types'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('get-number-types'));
        $response->assertSuccessful();
    }
}
