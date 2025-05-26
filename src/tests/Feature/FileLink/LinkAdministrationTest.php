<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LinkAdministrationTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('admin.links.manage.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.index'));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        FileLink::factory()
            ->count(5)
            ->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('FileLink/Index')
                    ->has('is-admin')
            );
    }

    public function test_invoke_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('FileLink/Index')
                    ->has('is-admin')
            );
    }
}
