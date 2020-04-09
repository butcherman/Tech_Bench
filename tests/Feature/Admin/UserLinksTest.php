<?php

namespace Tests\Feature\Admin;

use App\FileLinks;
use Tests\TestCase;

class UserLinksTest extends TestCase
{
    protected $user, $installer, $links;

    public function setUp():void
    {
        Parent::setUp();

        $this->user = $this->getTech();
        $this->installer = $this->getInstaller();
        $this->links = factory(FileLinks::class, 10)->create([
            'user_id' => $this->user->user_id,
        ]);
    }

    public function test_visit_view_user_links_as_guest()
    {
        $response = $this->get(route('admin.user.links'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_view_user_links_no_permissions()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.links'));

        $response->assertStatus(403);
    }

    public function test_visit_view_user_links()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.user.links'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userLinks');
    }

    public function test_get_user_links_as_guest()
    {
        $response = $this->get(route('admin.user.showLinks', $this->user->user_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_user_links_no_permissions()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.showLinks', $this->user->user_id));

        $response->assertStatus(403);
    }

    public function test_get_user_links()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.user.showLinks', $this->user->user_id));

        $response->assertSuccessful();
        $response->assertViewIs('admin.linkDetails');
    }
}
