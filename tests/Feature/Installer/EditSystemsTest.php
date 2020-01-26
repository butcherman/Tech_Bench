<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use App\SystemTypes;
use App\CustomerSystems;
use App\SystemDataFields;

class EditSystemsTest extends TestCase
{
    protected $system, $dataFields;

    public function setUp(): void
    {
        Parent::setUp();

        $this->system = factory(SystemTypes::class)->create();
        $this->dataFields = factory(SystemDataFields::class, 5)->create([
            'sys_id' => $this->system->sys_id,
        ]);
    }

    public function test_visit_edit_systems_page_as_guest()
    {
        $response = $this->get(route('admin.systems.edit', $this->system->sys_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_edit_systems_page_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.systems.edit', $this->system->sys_id));

        $response->assertStatus(403);
    }

    public function test_visit_edit_systems_page_user_with_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->get(route('admin.systems.edit', $this->system->sys_id));

        $response->assertSuccessful();
        $response->assertViewIs('installer.editSystem');
    }

    public function test_visit_edit_systems_page_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.systems.edit', $this->system->sys_id));

        $response->assertSuccessful();
        $response->assertViewIs('installer.editSystem');
    }

    public function test_submit_edit_systems_as_guest()
    {
        $data = [
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($this->dataFields as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_edit_systems_user_without_permission()
    {
        $data = [
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($this->dataFields as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getTech())->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertStatus(403);
    }

    public function test_submit_edit_systems_user_with_permission()
    {
        $data = [
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($this->dataFields as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_edit_systems_as_installer()
    {
        $data = [
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($this->dataFields as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_edit_systems_add_two_new_fields()
    {
        $data = [
            'name'        => 'New System Name',
            'dataOptions' => [],
            'newOptions'  => [],
        ];
        foreach ($this->dataFields as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $data['newOptions'][] = ['name' => 'New Option 1'];
        $data['newOptions'][] = ['name' => 'New Option 2'];
        $response = $this->actingAs($this->getInstaller())->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_edit_systems_name_validation_error()
    {
        $data = [
            'name'        => null,
            'dataOptions' => [],
        ];
        foreach ($this->dataFields as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_submit_edit_systems_data_validation_error()
    {
        $data = [
            'name'        => null,
            'dataOptions' => [],
        ];
        $response = $this->actingAs($this->getInstaller())->put(route('admin.systems.update', $this->system->sys_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('dataOptions');
    }

    public function test_delete_system_as_guest()
    {
        $response = $this->delete(route('admin.systems.destroy', $this->system->sys_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_delete_system_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->delete(route('admin.systems.destroy', $this->system->sys_id));

        $response->assertStatus(403);
    }

    public function test_delete_system_user_with_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->delete(route('admin.systems.destroy', $this->system->sys_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true, 'reason' => 'Equipment Successfully Deleted']);
    }

    public function test_delete_system_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->delete(route('admin.systems.destroy', $this->system->sys_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true, 'reason' => 'Equipment Successfully Deleted']);
    }

    public function test_delete_system_that_is_in_use()
    {
        // $cust = factory(Customers::class)->create();
        factory(CustomerSystems::class)->create([
            'sys_id' => $this->system->sys_id,
        ]);
        $response = $this->actingAs($this->getInstaller())->delete(route('admin.systems.destroy', $this->system->sys_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => false, 'reason' => 'Cannot delete this equipment.  It has Customers or Tech Tips assigned to it.  Please delete those first.']);
    }
}
