<?php

namespace Tests\Feature\Admin;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;

class UserDisabledTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        Parent::setUp();

        $this->user      = factory(User::class)->create([
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_visit_diabled_users_page_as_guest()
    {
        $response = $this->get(route('admin.user.show', 'inactive'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_diabled_users_page_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.user.show', 'inactive'));

        $response->assertStatus(403);
    }

    public function test_visit_diabled_users_page_basic_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Users'))->get(route('admin.user.show', 'inactive'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userDeleted');
    }

    public function test_visit_diabled_users_page()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.show', 'inactive'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userDeleted');
    }







    public function test_submit_diabled_user_page_as_guest()
    {
        $response = $this->get(route('admin.user.reactivate', $this->user->user_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_diabled_user_page_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.user.reactivate', $this->user->user_id));

        $response->assertStatus(403);
    }

    public function test_submit_diabled_user_page_basic_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Users'))->get(route('admin.user.reactivate', $this->user->user_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_diabled_user_page()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.reactivate', $this->user->user_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
