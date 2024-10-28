<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AboutTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_about_guest(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_about(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('about'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home/About')
                ->has('build')
                ->has('build_date')
            );
    }
}
