<?php

namespace Tests\Feature\FileLinks;

use App\Files;
use App\Customers;
use App\FileLinks;
use Tests\TestCase;
use App\FileLinkFiles;
use Illuminate\Http\File;
use App\CustomerFileTypes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $user     = $this->userWithoutPermission('Use File Links');
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
        $user     = $this->userWithoutPermission('Use File Links');
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
            'allowUp'         => true,
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
        $user     = $this->userWithoutPermission('Use File Links');
        $data = [
            'name'            => 'Updated Link Name',
            'expire'          => date('Y-m-d', strtotime('+90 days')),
            'allowUp'         => true,
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
            'allowUp'         => true,
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
            'allowUp'         => true,
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
            'name'            => 'Updated Link Name',
            'allowUp'         => true,
            'customerTag'     => null,
            'hasInstructions' => false,
            'instructions'    => '',
        ];
        $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('expire');
    }

    /*
    *   Link Instructions
    */

    //  Test get link instructions as a guest
    public function test_get_link_instructions_as_guest()
    {
        $response = $this->get(route('links.getInstructions', $this->link->link_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test get link instructions without permissions
    public function test_get_link_instructions_no_permissions()
    {
        $user     = $this->userWithoutPermission('Use File Links');
        $response = $this->actingAs($user)->get(route('links.getInstructions', $this->link->link_id));

        $response->assertStatus(403);
    }

    //  Test get link instructions as tech
    public function test_get_link_instructions()
    {
        $response = $this->actingAs($this->tech)->get(route('links.getInstructions', $this->link->link_id));

        $response->assertSuccessful();
        $response->assertJsonStructure(['note']);
    }

    //  Test try to update instructions as guest
    public function test_update_link_instructions_as_guest()
    {
        $data = [
            'note' => 'Here are some basic instructions to load',
        ];

        $response = $this->post(route('links.submitInstructions', $this->link->link_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test try to update instructions as user without permission
    public function test_update_link_instructions_no_permission()
    {
        $data = [
            'note' => 'Here are some basic instructions to load',
        ];

        $user     = $this->userWithoutPermission('Use File Links');
        $response = $this->actingAs($user)->post(route('links.submitInstructions', $this->link->link_id), $data);

        $response->assertStatus(403);
    }

    //  Test updating link instructions - valid
    public function test_update_link_instructions()
    {
        $data = [
            'instructions' => 'Here are some basic instructions to load',
        ];

        $response = $this->actingAs($this->tech)->post(route('links.submitInstructions', $this->link->link_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
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
        $user     = $this->userWithoutPermission('Use File Links');
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
        $user     = $this->userWithoutPermission('Use File Links');
        $response = $this->actingAs($user)->get(route('links.files.show', $this->link->link_id));

        $response->assertStatus(403);
    }

    //  Test trying to get the file link files as a valid user
    public function test_get_file_link_files()
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
        $user     = $this->userWithoutPermission('Use File Links');
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
    //  TODO - Test chunk file uploads
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
        $user     = $this->userWithoutPermission('Use File Links');
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

    //  Test moving a file to a customer
    public function test_move_file_to_customer()
    {
        Storage::fake(config('filesystems.paths.links'));
        Storage::fake(config('filesystems.paths.customers'));

        //  Create a new link and assign a customer to it
        $link = factory(FileLinks::class)->create([
            'cust_id' => $cust = factory(Customers::class)->create()
        ]);
        //  Place a test file for linking
        $testFile = new File(base_path().DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'testFiles'.DIRECTORY_SEPARATOR.'hi.png');
        $testFileName = 'testImg.png';
        $testFilePath = config('filesystems.paths.links') . DIRECTORY_SEPARATOR . $link->link_id.DIRECTORY_SEPARATOR;
        $newFilePath  = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$cust->cust_id.DIRECTORY_SEPARATOR;
        Storage::putFileAs($testFilePath, $testFile, $testFileName);
        //  Put the file in the database
        $fileData = factory(Files::class)->create([
            'file_name' => $testFileName,
            'file_link' => $testFilePath
        ]);
        factory(FileLinkFiles::class)->create([
            'link_id'  => $link->link_id,
            'file_id'  => $fileData->file_id,
            'user_id'  => null,
            'added_by' => 'Billy Bob',
            'upload'   => 1
        ]);

        //  Verify that the file is in the correct place
        Storage::disk('local')->assertExists($testFilePath.$testFileName);

        //  Prep data for moving the file
        $data = [
            'fileID' => $fileData->file_id,
            'fileName' => $fileData->file_name,
            'fileType' => CustomerFileTypes::all()->first()->file_type_id
        ];

        $response = $this->actingAs($this->tech)->put(route('links.files.update', $link->link_id), $data);

        $response->assertSuccessful();
        //  Verify the file is in the new location and no longer in the link
        Storage::disk('local')->assertExists($newFilePath . $testFileName);
        Storage::disk('local')->assertMissing($testFilePath . $testFileName);

        //  Try to move the file again
        $moveAgain = $this->actingAs($this->tech)->put(route('links.files.update', $link->link_id), $data);

        $moveAgain->assertExactJson(['success' => false]);
    }

    //  Try to move a file that is for some reason missing from the file link
    public function test_move_missing_file()
    {
        Storage::fake(config('filesystems.paths.links'));
        Storage::fake(config('filesystems.paths.customers'));

        //  Create a new link and assign a customer to it
        $link = factory(FileLinks::class)->create([
            'cust_id' => $cust = factory(Customers::class)->create()
        ]);
        //  Place a test file for linking
        $testFile = new File(base_path() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'testFiles' . DIRECTORY_SEPARATOR . 'hi.png');
        $testFileName = 'testImg.png';
        $testFilePath = config('filesystems.paths.links') . DIRECTORY_SEPARATOR . $link->link_id . DIRECTORY_SEPARATOR;
        $newFilePath  = config('filesystems.paths.customers') . DIRECTORY_SEPARATOR . $cust->cust_id . DIRECTORY_SEPARATOR;

        //  Put the file in the database
        $fileData = factory(Files::class)->create([
            'file_name' => $testFileName,
            'file_link' => $testFilePath
        ]);
        factory(FileLinkFiles::class)->create([
            'link_id'  => $link->link_id,
            'file_id'  => $fileData->file_id,
            'user_id'  => null,
            'added_by' => 'Billy Bob',
            'upload'   => 1
        ]);

        $data = [
            'fileID' => $fileData->file_id,
            'fileName' => $fileData->file_name,
            'fileType' => CustomerFileTypes::all()->first()->file_type_id
        ];

        $response = $this->actingAs($this->tech)->put(route('links.files.update', $link->link_id), $data);

        //  This should not crash app, but it should trigger exception
        $response->assertSuccessful();
        $response->assertExactJson(['success' => false]);
    }
}
