<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\FileLinks;
use App\FileLinkFiles;
use App\User;
use App\UserPermissions;

class GuestFileLinksTest extends TestCase
{
    private $user, $link, $file, $cust;

    public function setUp(): void
    {
        Parent::setup();

        // $this->cust = factory(Customers::class)->create();
        $this->user = $this->getTech();
        $this->link = factory(FileLinks::class)->create();
        $this->file = factory(FileLinkFiles::class, 5)->create([
            'link_id' => $this->link->link_id,
            'user_id' => $this->user->user_id
        ]);
    }

    //  Verify that someone can visit the link landing page
    public function test_guest_links_index_page()
    {
        $result = $this->get(route('file-links.index'));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestIndex');
    }

    //  Verify a guest can visit the link detials page
    public function test_guest_page_access()
    {
        $result = $this->get(route('file-links.show', $this->link->link_hash));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestDetails');
        $this->assertGuest();
    }

    //  Verify that a logged in user can visit the link details page
    public function test_guest_page_access_as_tech()
    {
        $result = $this->actingAs($this->user)->get(route('file-links.show', $this->link->link_hash));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestDetails');
    }

    //  Even a user that does not have permissions should be able to access this page
    public function test_guest_page_access_no_permissions()
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
        $result = $this->actingAs($user)->get(route('file-links.show', $this->link->link_hash));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestDetails');
    }

    //  Verify that visiting a bad link returns the proper view
    public function test_bad_link_hash()
    {
        $result = $this->get(route('file-links.show', Str::random(15)));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestBadLink');
    }

    //  Verify that visisiting an expired link returns the proper view
    public function test_expired_link()
    {
        $link = factory(FileLinks::class)->create([
            'expire' => Carbon::yesterday()
        ]);

        $result = $this->get(route('file-links.show', $link->link_hash));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestExpiredLink');
    }

    //  Test visiting a page that does nnot have anything for the guest to do
    public function test_link_with_nothing_to_do()
    {
        $link = factory(FileLinks::class)->create([
            'allow_upload' => false
        ]);

        $result = $this->get(route('file-links.show', $link->link_hash));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestDeadLink');
    }
}
