<?php

namespace Tests\Feature\Admin;

use App\SystemDataFieldTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentInformationControllerTest extends TestCase
{
    public function test_index_guest()
    {
        $response = $this->get(route('admin.equipment.equipment_information'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs($this->getUserWIthoutPermission('Manage Equipment'))->get(route('admin.equipment.equipment_information'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs($this->getUserWIthPermission('Manage Equipment'))->get(route('admin.equipment.equipment_information'));
        $response->assertSuccessful();
        $response->assertViewIs('admin.equipment.equipmentInformation');
    }

    public function test_submit_new_field_guest()
    {
        $test = factory(SystemDataFieldTypes::class)->make();
        $data = [
            'name' => $test->name,
            'hidden' => true,
        ];

        $response = $this->post(route('admin.equipment.new_field'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_new_field_no_permission()
    {
        $test = factory(SystemDataFieldTypes::class)->make();
        $data = [
            'name' => $test->name,
            'hidden' => true,
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->post(route('admin.equipment.new_field'), $data);
        $response->assertStatus(403);
    }

    public function test_submit_new_field()
    {
        $test = factory(SystemDataFieldTypes::class)->make();
        $data = [
            'name' => $test->name,
            'hidden' => true,
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->post(route('admin.equipment.new_field'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_field_name_guest()
    {
        $test = factory(SystemDataFieldTypes::class)->create();
        $data = [
            'name' => $test->name,
            'data_type_id' => $test->data_type_id,
            'hidden' => true,
        ];

        $response = $this->put(route('admin.equipment.submit_field'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_field_name_no_permission()
    {
        $test = factory(SystemDataFieldTypes::class)->create();
        $data = [
            'name' => $test->name,
            'data_type_id' => $test->data_type_id,
            'hidden' => true,
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->put(route('admin.equipment.submit_field'), $data);
        $response->assertStatus(403);
    }

    public function test_submit_field_name()
    {
        $test = factory(SystemDataFieldTypes::class)->create();
        $data = [
            'name' => $test->name,
            'data_type_id' => $test->data_type_id,
            'hidden' => true,
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->put(route('admin.equipment.submit_field'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_delete_field_guest()
    {
        $field = factory(SystemDataFieldTypes::class)->create();
        $response = $this->delete(route('admin.equipment.delete_field', $field->data_type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_delete_field_no_permission()
    {
        $field = factory(SystemDataFieldTypes::class)->create();
        $response = $this->actingAs($this->getUserWIthoutPermission('Manage Equipment'))->delete(route('admin.equipment.delete_field', $field->data_type_id));
        $response->assertStatus(403);
    }

    public function test_delete_field()
    {
        $field = factory(SystemDataFieldTypes::class)->create();
        $response = $this->actingAs($this->getUserWIthPermission('Manage Equipment'))->delete(route('admin.equipment.delete_field', $field->data_type_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
