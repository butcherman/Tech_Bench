<?php

namespace Tests\Feature\API;

use App\Models\User;
use Tests\TestCase;

class GetUploadFileTypesTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('file-types'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('file-types'));

        $response->assertSuccessful();
    }
}
