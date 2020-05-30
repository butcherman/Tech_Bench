<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentTypesControllerTest extends TestCase
{
    // protected $testCat;

    public function setUp():void
    {
        Parent::setup();

        // $this->testCat = factory(SystemCategories::class)->create();
        // factory(SystemTypes::class, 10)->create(['cat_id' => $this->testCat->cat_id]);
    }

    public function test_index_guest()
    {
        $response = $this->get(route('admin.equipment.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->get(route('admin.equipment.index'));

        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->get(route('admin.equipment.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.equipment.index');
    }
}
