<?php

namespace Tests\Feature\FileLinks;

use App\User;
use App\UserPermissions;
use App\Customers;
use App\FileLinks;
use Tests\TestCase;
use App\FileLinkFiles;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\CustomerFileTypes;

class FileLinkDetailsTest extends TestCase
{
    private $tech, $link, $file, $cust;

    public function setUp():void
    {
        Parent::setup();

        // $this->cust = factory(Customers::class)->create();
        $this->tech = $this->getTech();
        $this->link = factory(FileLinks::class)->create();
        $this->file = factory(FileLinkFiles::class, 5)->create([
            'link_id' => $this->link->link_id,
            'user_id' => $this->tech->user_id
        ]);
    }

    /*
    *   Link Details and Page View
    */
    //  Visit details page as guest
    public function test_details_page_as_guest()
    {
        $response = $this->get(route('links.details', [$this->link->link_id, $this->link->link_name]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Visit details page as a tech that does not have access
    public function test_details_page_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->get(route('links.details', [$this->link->link_id, $this->link->link_name]));

        $response->assertStatus(403);
    }

    //  Visit details page for a link that does not actually exist
    public function test_details_page_bad_link_id()
    {
        $response = $this->actingAs($this->tech)->get(route('links.details', [5521421452, $this->link->link_name]));

        $response->assertSuccessful();
        $response->assertViewIs('links.badLink');
    }

    //  Visit details page as tech
    public function test_details_page()
    {
        $response = $this->actingAs($this->tech)->get(route('links.details', [$this->link->link_id, $this->link->link_name]));

        $response->assertSuccessful();
        $response->assertViewIs('links.details');
    }

    //  Try to pull JSON details of link as Guest
    public function test_get_link_details_as_guest()
    {
        $response = $this->get(route('links.data.show', $this->link->link_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to pull JSON details of link without permissions
    public function test_get_link_details_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->get(route('links.data.show', $this->link->link_id));

        $response->assertStatus(403);
    }

    //  Try to pull JSON details of link as User
    public function test_get_link_details()
    {
        $response = $this->actingAs($this->tech)->get(route('links.data.show', $this->link->link_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'link_id',
            'user_id',
            'cust_id',
            'cust_name',
            'link_hash',
            'link_name',
            'exp_format',
            'expired',
            'exp_stamp',
            'allow_upload',
            'file_count',
            'note'
        ]);
    }

    //  Try to update the link details as a guest
    public function test_update_link_details_as_guest()
    {
        $data = [
            'name'            => 'Updated Link Name',
            'expire'          => date('Y-m-d', strtotime('+90 days')),
            'allow_upload'    => 'on',
            'customerTag'     => null,
            'hasInstructions' => false,
            'instructions'    => '',
        ];
        $response = $this->put(route('links.data.update', $this->link->link_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to update the link details as user wihtout permissions
    public function test_update_link_details_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );
        $data = [
            'name'            => 'Updated Link Name',
            'expire'          => date('Y-m-d', strtotime('+90 days')),
            'allow_upload'    => 'on',
            'customerTag'     => null,
            'hasInstructions' => false,
            'instructions'    => '',
        ];
        $response = $this->actingAs($user)->put(route('links.data.update', $this->link->link_id), $data);

        $response->assertStatus(403);
    }

    //  Try to update the link details as a tech
    public function test_update_link_details_valid()
    {
        $data = [
            'name'            => 'Updated Link Name',
            'expire'          => date('Y-m-d', strtotime('+90 days')),
            'allow_upload'    => 'on',
            'customerTag'     => null,
            'hasInstructions' => false,
            'instructions'    => '',
        ];
        $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test Name validation error
    public function test_update_link_details_name_validation_error()
    {
        $data = [
            'expire'          => date('Y-m-d', strtotime('+90 days')),
            'allow_upload'    => 'on',
            'customerTag'     => null,
            'hasInstructions' => false,
            'instructions'    => '',
        ];
        $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    //  Test Expire validation error
    public function test_update_link_details_expire_validation_error()
    {
        $data = [
            'name'         => 'Updated Link Name',
            'allow_upload'    => 'on',
            'customerTag'     => null,
            'hasInstructions' => false,
            'instructions'    => '',
        ];
        $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('expire');
    }

    /*
    *   Delete Link
    */
    //  Try to delete the link as a guest
    public function test_delete_link_as_guest()
    {
        $response = $this->delete(route('links.data.destroy', $this->link->link_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to delete a link as a user without permissions
    public function test_delete_link_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->delete(route('links.data.destroy', $this->link->link_id));

        $response->assertStatus(403);
    }

    //  Try to delete the link as a tech
    public function test_delete_link()
    {
        $response = $this->actingAs($this->tech)->delete(route('links.data.destroy', $this->link->link_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    /*
    *   File Link Files
    */
    //  Test trying to get file link files as a guest
    public function test_get_files_as_guest()
    {
        $response = $this->get(route('links.files.show', $this->link->link_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Tst trying to get file link files wihtout permissions
    public function test_get_files_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->get(route('links.files.show', $this->link->link_id));

        $response->assertStatus(403);
    }

    //  Test trying to get the file link files as a valid user
    public function test_get_files()
    {
        $response = $this->actingAs($this->tech)->get(route('links.files.show', $this->link->link_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['link_file_id', 'link_id', 'file_id', 'user_id', 'added_by', 'upload', 'note', 'created_at']]);
    }

    //  Try to add a file to the link as a guest
    public function test_add_file_as_guest()
    {
        Storage::fake(config('filesystems.paths.links'));
        $data = [
            'linkID' => $this->link->link_id,
            'file' => $file = UploadedFile::fake()->image('addedImage.jpg')
        ];

        $response = $this->post(route('links.files.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
        Storage::disk('local')->assertMissing(config('filesystems.paths.links').DIRECTORY_SEPARATOR.$this->link->link_id.DIRECTORY_SEPARATOR.'addedImage.jpg');
    }

    //  Try to add a file to the link without permissions
    public function test_add_file_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        Storage::fake(config('filesystems.paths.links'));
        $data = [
            'linkID' => $this->link->link_id,
            'file' => $file = UploadedFile::fake()->image('addedImage33.jpg')
        ];

        $response = $this->actingAs($user)->post(route('links.files.store'), $data);

        $response->assertStatus(403);
        Storage::disk('local')->assertMissing(config('filesystems.paths.links') . DIRECTORY_SEPARATOR . $this->link->link_id . DIRECTORY_SEPARATOR . 'addedImage33.jpg');
    }

    //  Try to add a file to the link as a tech
    public function test_add_file()
    {
        Storage::fake(config('filesystems.paths.links'));
        $data = [
            'linkID' => $this->link->link_id,
            'file' => $file = UploadedFile::fake()->image('addedImage.jpg')
        ];

        $response = $this->actingAs($this->tech)->post(route('links.files.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
        Storage::disk('local')->assertExists(config('filesystems.paths.links').DIRECTORY_SEPARATOR.$this->link->link_id.DIRECTORY_SEPARATOR.'addedImage.jpg');
    }

    //  Try to delete a file as a guest
    public function test_delete_file_as_guest()
    {
        $response = $this->delete(route('links.files.destroy', $this->file[0]->link_file_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to delete a file without permissions
    public function test_delete_file_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->delete(route('links.files.destroy', $this->file[0]->link_file_id));

        $response->assertStatus(403);
    }

    //  Try to delete a file as a tech
    public function test_delete_file()
    {
        $response = $this->actingAs($this->tech)->delete(route('links.files.destroy', $this->file[0]->link_file_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
