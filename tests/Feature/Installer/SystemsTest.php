<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use App\SystemTypes;

class SystemsTest extends TestCase
{
    protected $systems;

    public function setUp(): void
    {
        Parent::setUp();

        $this->systems = factory(SystemTypes::class, 5)->create();
    }

    public function test_visit_systems_page_as_guest()
    {
        $response = $this->get(route('admin.systems.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_systems_page_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.systems.index'));

        $response->assertStatus(403);
    }

    public function test_visit_systems_page_user_with_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->get(route('admin.systems.index'));

        $response->assertSuccessful();
        $response->assertViewIs('installer.systemsList');
    }

    public function test_visit_systems_page_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.systems.index'));

        $response->assertSuccessful();
        $response->assertViewIs('installer.systemsList');
    }
}
