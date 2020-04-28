<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use App\SystemTypes;
use App\SystemCategories;

class CategoriesTest extends TestCase
{
    protected $categories;

    public function setUp(): void
    {
        Parent::setUp();

        $this->categories = factory(SystemCategories::class, 5)->create();
    }

    public function test_visit_categories_page_as_guest()
    {
        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_categories_page_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.categories.index'));

        $response->assertStatus(403);
    }

    public function test_visit_categories_page_user_with_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->get(route('admin.categories.index'));

        $response->assertSuccessful();
        $response->assertViewIs('installer.categoryList');
    }

    public function test_visit_categories_page_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.categories.index'));

        $response->assertSuccessful();
        $response->assertViewIs('installer.categoryList');
    }





    public function test_submit_new_category_as_guest()
    {
        $data = [
            'name' => 'New Category Name',
        ];
        $response = $this->post(route('admin.categories.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_new_category_user_without_permission()
    {
        $data = [
            'name' => 'New Category Name',
        ];
        $response = $this->actingAs($this->getTech())->post(route('admin.categories.store'), $data);

        $response->assertStatus(403);
    }

    public function test_submit_new_category_user_with_permission()
    {
        $data = [
            'name' => 'New Category Name',
        ];
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->post(route('admin.categories.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_new_category_as_installer()
    {
        $data = [
            'name' => 'New Category Name',
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.categories.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_new_category_name_validation_error()
    {
        $data = [
            'name' => null,
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.categories.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_submit_new_category_validation_error_used_name()
    {
        $data = [
            'name' => $this->categories[0]->name,
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.categories.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }






    public function test_delete_category_as_guest()
    {
        $response = $this->delete(route('admin.categories.destroy', $this->categories[0]->cat_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_delete_category_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->delete(route('admin.categories.destroy', $this->categories[0]->cat_id));

        $response->assertStatus(403);
    }

    public function test_delete_category_user_with_permission()
    {
        $response = $this->actingAs($this->userWithPermission('Manage Equipment'))->delete(route('admin.categories.destroy', $this->categories[0]->cat_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_delete_category_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->delete(route('admin.categories.destroy', $this->categories[0]->cat_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_delete_category_that_has_systems_assigned()
    {
        factory(SystemTypes::class, 5)->create([
            'cat_id' => $this->categories[3]->cat_id,
        ]);

        $response = $this->actingAs($this->getInstaller())->delete(route('admin.categories.destroy', $this->categories[3]->cat_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => false, 'reason' => 'Category still in use.  You must delete all systems attached to this category first.']);
    }

}
