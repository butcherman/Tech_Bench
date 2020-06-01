<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

use App\CustomerSystems;
use App\CustomerSystemData;
use App\SystemTypes;
use App\SystemDataFields;
use App\SystemDataFieldTypes;

class EquipmentTypesControllerTest extends TestCase
{
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

    public function test_create_guest()
    {
        $response = $this->get(route('admin.equipment.types.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->get(route('admin.equipment.types.create'));

        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->get(route('admin.equipment.types.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.equipment.equipmentForm');
    }

    public function test_store_guest()
    {
        $makeEquip = factory(SystemTypes::class)->make();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => $makeEquip->name,
            'cat_id' => $makeEquip->cat_id,
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->post(route('admin.equipment.types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $makeEquip = factory(SystemTypes::class)->make();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => $makeEquip->name,
            'cat_id' => $makeEquip->cat_id,
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->post(route('admin.equipment.types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $makeEquip = factory(SystemTypes::class)->make();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => $makeEquip->name,
            'cat_id' => $makeEquip->cat_id,
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->post(route('admin.equipment.types.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_edit_guest()
    {
        $sys = factory(SystemTypes::class)->create();
        $response = $this->get(route('admin.equipment.types.edit', $sys->sys_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $sys = factory(SystemTypes::class)->create();
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->get(route('admin.equipment.types.edit', $sys->sys_id));

        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $sys = factory(SystemTypes::class)->create();
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->get(route('admin.equipment.types.edit', $sys->sys_id));

        $response->assertSuccessful();
        $response->assertViewIs('admin.equipment.equipmentForm');
    }

    public function test_update_guest()
    {
        $makeEquip = factory(SystemTypes::class)->create();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => 'New Equipment Name',
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->put(route('admin.equipment.types.update', $makeEquip->sys_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $makeEquip = factory(SystemTypes::class)->create();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => 'New Equipment Name',
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->put(route('admin.equipment.types.update', $makeEquip->sys_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $makeEquip = factory(SystemTypes::class)->create();
        $makeField = factory(SystemDataFieldTypes::class, 3)->create();
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => 'New Equipment Name',
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->put(route('admin.equipment.types.update', $makeEquip->sys_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_update_fail()
    {
        $makeEquip = factory(SystemTypes::class)->create();
        $makeField = factory(SystemDataFields::class, 3)->create(['sys_id' => $makeEquip->sys_id]);
        $custField = factory(SystemDataFieldTypes::class, 2)->make();

        $data = [
            'name'   => 'New System Name',
            'system_data_fields' => [],
        ];
        foreach($makeField as $field)
        {
            factory(CustomerSystemData::class)->create([
                'field_id' => $field->field_id,
            ]);
        }
        foreach($custField as $field)
        {
            $data['system_data_fields'][] = $field->name;
        }

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->put(route('admin.equipment.types.update', $makeEquip->sys_id), $data);
        $response->assertStatus(428);
    }

    public function test_delete_guest()
    {
        $sys = factory(SystemTypes::class)->create();
        $response = $this->delete(route('admin.equipment.types.destroy', $sys->sys_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_delete_no_permission()
    {
        $sys = factory(SystemTypes::class)->create();
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Equipment'))->delete(route('admin.equipment.types.destroy', $sys->sys_id));
        $response->assertStatus(403);
    }

    public function test_delete()
    {
        $sys = factory(SystemTypes::class)->create();
        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->delete(route('admin.equipment.types.destroy', $sys->sys_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_delete_fail()
    {
        $sys = factory(SystemTypes::class)->create();
        factory(CustomerSystems::class, 5)->create(['sys_id' => $sys->sys_id]);

        $response = $this->actingAs($this->getUserWithPermission('Manage Equipment'))->delete(route('admin.equipment.types.destroy', $sys->sys_id));
        $response->assertStatus(428);
    }
}
