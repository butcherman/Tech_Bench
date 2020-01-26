<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use App\FileLinks;
use Tests\TestCase;
use App\FileLinkFiles;
use App\UserPermissions;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;


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
        $user     = $this->userWithoutPermission('Use File Links');
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

    //  Test visiting a page that does not have anything for the guest to do
    public function test_link_with_nothing_to_do()
    {
        $link = factory(FileLinks::class)->create([
            'allow_upload' => false
        ]);

        $result = $this->get(route('file-links.show', $link->link_hash));

        $result->assertSuccessful();
        $result->assertViewIs('links.guestDeadLink');
    }

    /*
    *   Test Getting the files for the link
    */
    //  Verify a guest can get the files for the link
    public function test_files_page_access()
    {
        $result = $this->get(route('file-links.getFiles', $this->link->link_hash));

        $result->assertSuccessful();
        $result->assertJsonStructure([['link_file_id', 'link_id', 'file_id']]);
        $this->assertGuest();
    }

    //  Verify that a logged in user can get the files
    public function test_files_page_access_as_tech()
    {
        $result = $this->actingAs($this->user)->get(route('file-links.getFiles', $this->link->link_hash));

        $result->assertSuccessful();
        $result->assertJsonStructure([['link_file_id', 'link_id', 'file_id']]);
    }

    //  Even a user that does not have permissions should be able to get files
    public function test_files_page_access_no_permissions()
    {
        $user     = $this->userWithoutPermission('Use File Links');
        $result = $this->actingAs($user)->get(route('file-links.getFiles', $this->link->link_hash));

        $result->assertSuccessful();
        $result->assertJsonStructure([['link_file_id', 'link_id', 'file_id']]);
    }

    //  Test adding a new file as a guest
    //  TODO - test chunk file uploads
    public function test_adding_file_as_guest()
    {
        Storage::fake(config('filesystems.paths.links'));
        $imgName = Str::random(5).'.jpg';
        $data = [
            'name' => 'Billy Bob',
            'file' => $file = UploadedFile::fake()->image($imgName),
            'note' => Str::random(20)
        ];

        $response = $this->post(route('file-links.show', $this->link->link_hash), $data);

        $response->assertSuccessful();
        Storage::disk('local')->assertExists(config('filesystems.paths.links').DIRECTORY_SEPARATOR.$this->link->link_id . DIRECTORY_SEPARATOR .$imgName);
        $this->assertGuest();
    }

    //  Test adding a new file as a user
    public function test_adding_file_as_user()
    {
        Storage::fake(config('filesystems.paths.links'));
        $imgName = Str::random(5) . '.jpg';
        $data = [
            'name' => 'Billy Bob',
            'file' => $file = UploadedFile::fake()->image($imgName),
            'note' => Str::random(20)
        ];

        $response = $this->actingAs($this->user)->post(route('file-links.show', $this->link->link_hash), $data);

        $response->assertSuccessful();
        Storage::disk('local')->assertExists(config('filesystems.paths.links') . DIRECTORY_SEPARATOR . $this->link->link_id . DIRECTORY_SEPARATOR . $imgName);
    }

    //  Test adding a new file as a user that does not have permissions
    public function test_adding_file_as_user_no_permissions()
    {
        $user     = $this->userWithoutPermission('Use File Links');
        $imgName = Str::random(5) . '.jpg';
        $data = [
            'name' => 'Billy Bob',
            'file' => $file = UploadedFile::fake()->image($imgName),
            'note' => Str::random(20)
        ];

        $response = $this->actingAs($user)->post(route('file-links.show', $this->link->link_hash), $data);

        $response->assertSuccessful();
        Storage::disk('local')->assertExists(config('filesystems.paths.links') . DIRECTORY_SEPARATOR . $this->link->link_id . DIRECTORY_SEPARATOR . $imgName);
    }

    //  Test that after a file is uploaded, and the 'notify' route is triggered, it works
    public function test_notification_route()
    {
        Notification::fake();
        $data = [
            '_complete' => true,
            'count'     => 1
        ];
        $response = $this->put(route('file-links.show', $this->link->link_hash), $data);

        $response->assertSuccessful();
        //  TODO - test the notification is actually sent out
        // Notification::assertSentTo($this->user, NewFileUpload::class);
    }
}
