<?php

namespace Tests\Feature\FileLinks;

use App\Customers;
use App\FileLinks;
use Tests\TestCase;
use App\FileLinkFiles;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileLinkDetailsTest extends TestCase
{
    private $tech, $link, $file, $cust;

    // use RefreshDatabase;

    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    // public function setUp():void
    // {
    //     Parent::setup();

    //     // $this->cust = factory(Customers::class)->create();
    //     $this->tech = $this->getTech();
    //     $this->link = factory(FileLinks::class)->create();
    //     $this->file = factory(FileLinkFiles::class, 5)->create([
    //         'link_id' => $this->link->link_id,
    //         'user_id' => $this->tech->user_id
    //     ]);
    // }

    // /*
    // *   Link Details and Page View
    // */
    // //  Visit details page as guest
    // public function testDetailsPageGuest()
    // {
    //     $response = $this->get(route('links.details', [$this->link->link_id, $this->link->link_name]));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Visit details page as tech
    // public function testDetailsPageTech()
    // {
    //     $response = $this->actingAs($this->tech)->get(route('links.details', [$this->link->link_id, $this->link->link_name]));

    //     $response->assertSuccessful();
    //     $response->assertViewIs('links.details');
    // }

    // //  Try to pull JSON details of link as Guest
    // public function testGetDetailsGuest()
    // {
    //     $response = $this->get(route('links.data.show', $this->link->link_id));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to pull JSON details of link as Tech
    // public function testGetDetailsTech()
    // {
    //     $response = $this->actingAs($this->tech)->get(route('links.data.show', $this->link->link_id));

    //     $response->assertSuccessful();
    //     $response->assertJsonStructure(['link_id', 'user_id', 'cust_id', 'link_hash', 'link_name', 'note', 'expire']);
    // }

    // //  Try to update the link details as a guest
    // public function testupdateDetailsGuest()
    // {
    //     $data = [
    //         'name'         => 'Updated Link Name',
    //         'expire'       => date('Y-m-d', strtotime('+90 days')),
    //         'allow_upload' => 'on'
    //     ];
    //     $response = $this->put(route('links.data.update', $this->link->link_id), $data);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to update the link details as a tech
    // public function testUpdateDetailsTech()
    // {
    //     $data = [
    //         'name'         => 'Updated Link Name',
    //         'expire'       => date('Y-m-d', strtotime('+90 days')),
    //         'allow_upload' => 'on'
    //     ];
    //     $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }

    // //  Test Name validation error
    // public function testDetailsValidationName()
    // {
    //     $data = [
    //         'expire'       => date('Y-m-d', strtotime('+90 days')),
    //         'allow_upload' => 'on'
    //     ];
    //     $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors('name');
    // }

    // //  Test Expire validation error
    // public function testDetailseValidationExpiration()
    // {
    //     $data = [
    //         'name'         => 'Updated Link Name',
    //         'allow_upload' => 'on'
    //     ];
    //     $response = $this->actingAs($this->tech)->put(route('links.data.update', $this->link->link_id), $data);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors('expire');
    // }

    // /*
    // *   File Link Customers
    // */
    // //  Test linking the link to a customer as a guest
    // public function testUpdateCustomerGuest()
    // {
    //     $data = [
    //         'customer_tag' => $this->cust->cust_id.' - '.$this->cust->name
    //     ];

    //     $response = $this->post(route('links.updateCustomer', $this->link->link_id), $data);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Test linking the link to a customer as a tech
    // public function testUpdateCustomerTech()
    // {
    //     $data = [
    //         'customer_tag' => $this->cust->cust_id.' - '.$this->cust->name
    //     ];

    //     $response = $this->actingAs($this->tech)->post(route('links.updateCustomer', $this->link->link_id), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }

    // /*
    // *   File Link Instructions
    // */
    // //  Try to update a links instructions as a guest
    // public function testUpdateInstructionsGuest()
    // {
    //     $data = [
    //         'note' => 'This is some custom instructions for the link'
    //     ];

    //     $response = $this->post(route('links.instructions', $this->link->link_id), $data);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to update a links instructions with blank note as a tech
    // public function testUpdateINstructionsNoNote()
    // {
    //     $data = [
    //         'note' => NULL
    //     ];

    //     $response = $this->actingAs($this->tech)->post(route('links.instructions', $this->link->link_id), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }

    // //  Try to update a links instructions as a tech
    // public function testUpdateInstructionsTech()
    // {
    //     $data = [
    //         'note' => 'This is some custom instructions for the link'
    //     ];

    //     $response = $this->actingAs($this->tech)->post(route('links.instructions', $this->link->link_id), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }

    // //  Try to pull JSON instructions as guest
    // public function testGetInstructionsGuest()
    // {
    //     $response = $this->get(route('links.instructions', $this->link->link_id));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to pull JSON instructions as Tech
    // public function testGetInstructions()
    // {
    //     $response = $this->actingAs($this->tech)->get(route('links.instructions', $this->link->link_id));

    //     $response->assertSuccessful();
    //     $response->assertJsonStructure(['note']);
    // }

    // /*
    // *   File Link Files
    // */
    // //  Try to add a file to the link as a guest
    // public function testAddFileGuest()
    // {
    //     $data = [
    //         'linkID' => $this->link->link_id,
    //         'file' => $file = UploadedFile::fake()->image('addedImage.jpg')
    //     ];

    //     $response = $this->post(route('links.files.store'), $data);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    //     Storage::disk('local')->assertMissing(config('filesystems.paths.links').DIRECTORY_SEPARATOR.$this->link->link_id.DIRECTORY_SEPARATOR.'addedImage.jpg');
    // }

    // //  Try to add a file to the link as a tech
    // public function testAddFileTech()
    // {
    //     $data = [
    //         'linkID' => $this->link->link_id,
    //         'file' => $file = UploadedFile::fake()->image('addedImage.jpg')
    //     ];

    //     $response = $this->actingAs($this->tech)->post(route('links.files.store'), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    //     Storage::disk('local')->assertExists(config('filesystems.paths.links').DIRECTORY_SEPARATOR.$this->link->link_id.DIRECTORY_SEPARATOR.'addedImage.jpg');
    // }

    // //  Try to pull JSON file data as guest
    // public function testGetFilesGuest()
    // {
    //     $response = $this->get(route('links.files.show', $this->link->link_id));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to pull JSON file data as tech
    // public function testGetFilesTech()
    // {
    //     $response = $this->actingAs($this->tech)->get(route('links.files.show', $this->link->link_id));

    //     $response->assertSuccessful();
    //     $response->assertJsonStructure([['added_by', 'created_at', 'file_id', 'file_name', 'link_file_id', 'note', 'upload', 'timestamp']]);
    // }

    // //  Try to delete a file as a guest
    // public function deleteFileGuest()
    // {
    //     $response = $this->delete(route('links.files.destroy', $this->file[0]->link_file_id));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to delete a file as a tech
    // public function deleteFileTech()
    // {
    //     $response = $this->actingAs($this->user)->delete(route('links.files.destroy', $this->file[0]->link_file_id));

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }

    // /*
    // *   Delete Link
    // */

    // //  Try to delete the link as a guest
    // public function deleteLinkGuest()
    // {
    //     $response = $this->delete(route('links.data.destroy', $this->link->link_id));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Try to delete the link as a tech
    // public function deleteLinkTech()
    // {
    //     $response = $this->actingAs($this->tech)->delete(route('links.data.destroy', $this->link->link_id));

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }
}
