<?php

namespace Tests\Feature\FileLinks;

use App\Customers;
use Tests\TestCase;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewFileLinkTest extends TestCase
{
    private $customer;

    // use RefreshDatabase;

    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    // public function setUp():void
    // {
    //     Parent::setup();

    //     $this->customer = factory(Customers::class, 5)->create();
    // }

    // /*
    // *   Visit New Link Page
    // */

    // //  Test visit New Link page as guest
    // public function testVisitPageGuest()
    // {
    //     $response =  $this->get(route('links.data.create'));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Test visit New Link page as tech
    // public function testVisitPageTech()
    // {
    //     $user = $this->getTech();
    //     $response = $this->actingAs($user)->get(route('links.data.create'));

    //     $response->assertSuccessful();
    //     $response->assertViewIs('links.form.newLink');
    // }

    // /*
    // *   Attach customer to file link
    // */

    // //  Test trying to search customer to attach to link as guest
    // public function testCustomerListGuest()
    // {
    //     $response = $this->get(route('customer.searchID', $this->customer[0]->cust_id));

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Test trying to search customer to attach to link as tech
    // public function testCustomerListTech()
    // {
    //     $user = $this->getTech();
    //     $response = $this->actingAs($user)->get(route('customer.searchID', $this->customer[0]->cust_id));

    //     $response->assertSuccessful();
    //     $response->assertJsonCount(1);
    //     $response->assertJsonStructure([['cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip']]);
    // }

    // //  Test trying to search cusomter to attach to link without any data
    // public function testCustomerLinkNoData()
    // {
    //     $user = $this->getTech();
    //     $response = $this->actingAs($user)->get(route('customer.searchID', 'NULL'));

    //     $response->assertSuccessful();
    //     $response->assertJsonCount(5);
    //     $response->assertJsonStructure([['cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip']]);
    // }

    // /*
    // *   Submit New File Link
    // */

    // //  Test submitting a new file link as a guest
    // public function testSubmitLinkGuest()
    // {
    //     $data = [
    //         'name'   => 'Test File Link',
    //         'expire' => date('Y-m-d', strtotime('+30 days')),
    //         'file'   => null
    //     ];
    //     $response = $this->post(route('links.data.store'), $data);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Test submitting link
    // public function testSubmtitLinkTech()
    // {
    //     Storage::fake(config('filesystems.paths.links'));
    //     $user = $this->getTech();
    //     $data = [
    //         'cust_id' => $this->customer[0]->cust_id,
    //         'name'    => 'Test File Link',
    //         'expire'  => date('Y-m-d', strtotime('+30 days')),
    //         'file'    => $file = UploadedFile::fake()->image('randomImage.jpg')
    //     ];
    //     $response = $this->actingAs($user)->post(route('links.data.store'), $data);

    //     $response->assertSuccessful();
    //     $response->assertSeeText('uploaded successfully');

    //     unset($data['file']);
    //     $data['_completed'] = true;

    //     //  After file load completed
    //     $response2 = $this->actingAs($user)->post(route('links.data.store'), $data);

    //     $response2->assertSuccessful();
    //     $response2->assertJsonStructure(['link', 'name']);
    // }

    // //  Test Submitting a link without a file attached
    // public function testSubmitLinkNoFile()
    // {
    //     $user = $this->getTech();
    //     $data = [
    //         'cust_id'    => $this->customer[0]->cust_id,
    //         'name'       => 'Test File Link',
    //         'expire'     => date('Y-m-d', strtotime('+30 days')),
    //         '_completed' => true
    //     ];
    //     $response = $this->actingAs($user)->post(route('links.data.store'), $data);

    //     $response->assertSuccessful();
    //     $response->assertJsonStructure(['link', 'name']);
    // }

    // //  Test submitting a link without a customer selected
    // public function testSubmitLinkNoCustomer()
    // {
    //     $user = $this->getTech();
    //     $data = [
    //         'name'       => 'Test File Link',
    //         'expire'     => date('Y-m-d', strtotime('+30 days')),
    //         'file'       => Null,
    //         '_completed' => true
    //     ];
    //     $response = $this->actingAs($user)->post(route('links.data.store'), $data);

    //     $response->assertSuccessful();
    //     $response->assertJsonStructure(['link', 'name']);
    // }

    // //  Test No Name validation error
    // public function testSubmitLinkNoName()
    // {
    //     $user = $this->getTech();
    //     $data = [
    //         'expire'     => date('Y-m-d', strtotime('+30 days')),
    //         'file'       => Null,
    //         '_completed' => true
    //     ];
    //     $response = $this->actingAs($user)->post(route('links.data.store'), $data);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors('name');
    // }

    // //  Test no Expiration Date validation error
    // public function testSubmitLinkNoExpire()
    // {
    //     $user = $this->getTech();
    //     $data = [
    //         'name'       => 'Test File Link',
    //         'file'       => Null,
    //         '_completed' => true
    //     ];
    //     $response = $this->actingAs($user)->post(route('links.data.store'), $data);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors('expire');
    // }
}
