<?php

namespace Tests\Feature\Installer;

use App\SystemCategories;
use App\SystemDataFieldTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewSystemsTest extends TestCase
{
    protected $category;

    public function setUp(): void
    {
        Parent::setUp();

        $this->category = factory(SystemCategories::class)->create();
    }

    public function test_visit_new_systems_page_as_guest()
    {
        $response = $this->get(route('admin.systems.show', $this->category->name));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_new_systems_page_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.systems.show', $this->category->name));

        $response->assertStatus(403);
    }

    public function test_visit_new_systems_page_user_with_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->get(route('admin.systems.show', $this->category->name));

        $response->assertSuccessful();
        $response->assertViewIs('installer.newSystem');
    }

    public function test_visit_new_systems_page_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.systems.show', $this->category->name));

        $response->assertSuccessful();
        $response->assertViewIs('installer.newSystem');
    }

    public function test_submit_new_systems_as_guest()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach($options as $opt)
        {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->post(route('admin.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_new_systems_user_without_permission()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($options as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getTech())->post(route('admin.systems.store'), $data);

        $response->assertStatus(403);
    }

    public function test_submit_new_systems_user_with_permission()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($options as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->post(route('admin.systems.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_new_systems_as_installer()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($options as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->post(route('admin.systems.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_new_systems_add_two_new_fields()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($options as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $data['dataOptions'][] = ['name' => 'New Option 1'];
        $data['dataOptions'][] = ['name' => 'New Option 2'];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.systems.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_new_systems_category_validation_error()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => null,
            'name'        => 'New System Name',
            'dataOptions' => [],
        ];
        foreach ($options as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->post(route('admin.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('category');
    }

    public function test_submit_new_systems_name_validation_error()
    {
        $options = SystemDataFieldTypes::all()->random(5);
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => null,
            'dataOptions' => [],
        ];
        foreach ($options as $opt) {
            $data['dataOptions'][] = [
                'data_type_id' => $opt->data_type_id,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->post(route('admin.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_submit_new_systems_data_validation_error()
    {
        $data = [
            'category'    => $this->category->cat_id,
            'name'        => null,
            'dataOptions' => [],
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('dataOptions');
    }
}
