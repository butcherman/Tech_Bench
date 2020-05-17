<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminIndexTest extends TestCase
{
    public function test_visit_admin_index_as_guest()
    {
        $response = $this->get(route('admin.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_admin_index_page_without_permission()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertStatus(403);
    }

    public function test_visit_admin_index_page_with_manage_users()
    {
        $user = $this->getUserWithPermission('Manage Users');
        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }

    public function test_visit_admin_index_page_with_manage_user_roles()
    {
        $user = $this->getUserWithPermission('Manage User Roles');
        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }

    public function test_visit_admin_index_page_with_manage_customers()
    {
        $user = $this->getUserWithPermission('Manage Customers');
        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }

    public function test_visit_admin_index_page_with_manage_equipment()
    {
        $user = $this->getUserWithPermission('Manage Equipment');
        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }

    public function test_visit_admin_index_page_as_installer()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.index');
    }
}
