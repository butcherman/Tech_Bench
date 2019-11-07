<?php

namespace Tests\Feature\FileLinks;

use App\User;
use App\FileLinks;
use Tests\TestCase;
use App\UserPermissions;

class FileLinksIndexTest extends TestCase
{
    private $tech, $links;

    //  Create five random links
    public function setUp():void
    {
        Parent::setup();

        $this->tech  = $this->getTech();
        $this->links = factory(FileLinks::class, 5)->create([
            'user_id' => $this->tech->user_id
        ]);
    }

    //  Test visit index page as guest
    public function test_index_page_as_guest()
    {
        $response = $this->get(route('links.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test JSON call to get file links as guest
    public function test_fetch_links_as_guest()
    {
        $response = $this->get(route('links.user', [0]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit index page as user
    public function test_index_page()
    {
        $response = $this->actingAs($this->tech)->get(route('links.index'));

        $response->assertSuccessful();
        $response->assertViewIs('links.index');
    }

    //  Test visit index page as user without permissions
    public function test_index_page_no_permissions()
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

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertStatus(403);
    }

    //  Test JSON call to get the file links as registered user without permissions
    public function test_get_links_of_another_user_as_manager()
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

        $response = $this->actingAs($user)->get(route('links.user', [0]));

        $response->assertStatus(403);
    }

    //  Test JSON call to get the file links as user
    public function test_get_links_with_permission()
    {
        $response = $this->actingAs($this->tech)->get(route('links.user', [0]));

        $response->assertSuccessful();
        $response->assertJsonStructure([[
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
            'note']]);
    }

    //  Test JSON call to get the file links and pass their user information into the id field
    public function test_get_your_own_links()
    {
        $response = $this->actingAs($this->tech)->get(route('links.user', $this->tech->user_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([[
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
        ]]);
    }

    //  Test JSON call to get the file links of another user
    public function test_get_someone_elses_links()
    {
        $otherUser = $this->getTech();
        $response = $this->actingAs($this->tech)->get(route('links.user', $otherUser->user_id));

        $response->assertStatus(403);
    }

    //  Test JSON call to get the file links of another user as a manager
    public function test_get_links_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 1,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 1,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->get(route('links.user', $this->tech));

        $response->assertSuccessful();
        $response->assertJsonStructure([[
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
        ]]);
    }

    //  Test trying to disable a link while logged in as a guest
    public function test_disable_link_as_guest()
    {
        $response = $this->get(route('links.disable', $this->links[1]->link_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test trying to disable a link without permissions
    public function test_disable_link_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 1,
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

        $response = $this->actingAs($user)->get(route('links.disable', $this->links[1]->link_id));

        $response->assertStatus(403);
    }

    //  Test trying to disable a link
    public function test_disable_link()
    {
        $response = $this->actingAs($this->tech)->get(route('links.disable', $this->links[1]->link_id));

        $response->assertSuccessful();
    }
}
