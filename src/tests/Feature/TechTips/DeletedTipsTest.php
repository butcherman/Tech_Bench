<?php

namespace Tests\Feature\TechTips;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DeletedTipsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.tech-tips.deleted-tips'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->ActingAs($user)
            ->get(route('admin.tech-tips.deleted-tips'));

        $response->assertForbidden();
    }

    public function test_invoke()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->ActingAs($user)
            ->get(route('admin.tech-tips.deleted-tips'));
        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Deleted/Index')
                ->has('deleted-tips')
            );
    }
}
