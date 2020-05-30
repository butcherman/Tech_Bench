<?php

namespace Tests\Feature\Admin;

use App\SystemCategories;
use App\SystemTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentCategoriesControllerTest extends TestCase
{
    protected $testCat;

    public function setUp():void
    {
        Parent::setup();

        $this->testCat = factory(SystemCategories::class)->create();
        factory(SystemTypes::class, 10)->create(['cat_id' => $this->testCat->cat_id]);
    }

    public function test_create_guest()
    {
        $response = $this->get(route('admin.equipment.categories.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->get(route('admin.equipment.categories.create'));

        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->get(route('admin.equipment.categories.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.equipment.editCategory');
    }

    public function test_store_guest()
    {
        $data = [
            'name' => 'New Equipment Name'
        ];

        $response = $this->post(route('admin.equipment.categories.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'name' => 'New Equipment Name'
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->post(route('admin.equipment.categories.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'name' => 'New Equipment Name'
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->post(route('admin.equipment.categories.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_edit_guest()
    {
        $response = $this->get(route('admin.equipment.categories.edit', $this->testCat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->get(route('admin.equipment.categories.edit', $this->testCat->cat_id));

        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->get(route('admin.equipment.categories.edit', $this->testCat->cat_id));

        $response->assertSuccessful();
        $response->assertViewIs('admin.equipment.editCategory');
    }

    public function test_update_guest()
    {
        $data = [
            'name' => 'New Equipment Name'
        ];

        $response = $this->put(route('admin.equipment.categories.update', $this->testCat->cat_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'name' => 'New Equipment Name'
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->put(route('admin.equipment.categories.update', $this->testCat->cat_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $data = [
            'name' => 'New Equipment Name'
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->put(route('admin.equipment.categories.update', $this->testCat->cat_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_destroy_guest()
    {
        $response = $this->delete(route('admin.equipment.categories.destroy', $this->testCat->cat_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->delete(route('admin.equipment.categories.destroy', $this->testCat->cat_id));
        $response->assertStatus(403);
    }

    public function test_destroy_fail()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->delete(route('admin.equipment.categories.destroy', $this->testCat->cat_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }

    public function test_destroy()
    {
        $cat = factory(SystemCategories::class)->create();
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->delete(route('admin.equipment.categories.destroy', $cat->cat_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
