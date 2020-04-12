<?php

namespace Tests\Feature\FileLinks;

use App\Customers;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NewFileLinkTest extends TestCase
{
    private $customer, $user;

    public function setUp():void
    {
        Parent::setup();

        $this->user = $this->getTech();
        $this->customer = factory(Customers::class, 5)->create();
    }

    /*
    *   Visit main new link page tests
    */

    //  Test visit New Link page as guest
    public function test_visit_page_as_guest()
    {
        $response =  $this->get(route('links.data.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit New Link page as user
    public function test_visit_page_valid()
    {
        // $user = $this->getTech();
        $response = $this->actingAs($this->user)->get(route('links.data.create'));

        $response->assertSuccessful();
        $response->assertViewIs('links.newLink');
    }

    //  Test visit New Link page as user without permissions
    public function test_visit_page_no_permissions()
    {
        $user     = $this->userWithoutPermission('Use File Links');
        $response = $this->actingAs($user)->get(route('links.data.create'));

        $response->assertStatus(403);
    }

    /*
    *   Attach customer to file link
    */
    //  Test trying to search customer to attach to link by sending customer ID
    public function test_list_customers_valid_send_id()
    {
        $data = [
            'sortField' => 'name',
            'sortType'  => 'asc',
            'perPage'   => 25,
            'name' => $this->customer[0]->cust_id
        ];

        $response = $this->actingAs($this->user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }

    //  Test trying to search customer to attach to link by sending customer name
    public function test_list_customers_send_name()
    {
        $data = [
            'sortField' => 'name',
            'sortType'  => 'asc',
            'perPage'   => 25,
            'name' => $this->customer[0]->name
        ];

        $response = $this->actingAs($this->user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    //  Test trying to search cusomter to attach to link without any data
    public function test_list_customers_no_data()
    {
        $data = [
            'sortField' => 'name',
            'sortType'  => 'asc',
            'perPage'   => 25,
            'name' => null
        ];
        $response = $this->actingAs($this->user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    /*
    *   Submit New File Link
    */
    // //  Test submitting a new file link as a guest
    public function test_submit_link_as_guest()
    {
        $data = [
            'name'   => 'Test File Link',
            'expire' => date('Y-m-d', strtotime('+30 days')),
            'file'   => null
        ];
        $response = $this->post(route('links.data.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test submitting link without permissions
    public function test_submit_link_without_permissions()
    {
        $user     = $this->userWithoutPermission('Use File Links');
        $data = [
            'name'   => 'Test File Link',
            'expire' => date('Y-m-d', strtotime('+30 days')),
            'file'   => null
        ];
        $response = $this->actingAs($user)->post(route('links.data.store'), $data);

        $response->assertStatus(403);
    }

    public function test_submit_link_all_areas_filled()
    {
        Storage::fake(config('filesystems.paths.links'));
        $data = [
            'linkCustomer' => 'on',
            'customerID'   => $this->customer[0]->cust_id,
            'name'         => 'Test File Link',
            'allow_up'     => 'on',
            'expire'       => date('Y-m-d', strtotime('+30 days')),
            'file'         => $file = UploadedFile::fake()->image(Str::random(5).'.jpg')
        ];
        $response = $this->actingAs($this->user)->post(route('links.data.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);

        unset($data['file']);
        $data['_completed'] = true;

        //  After file load completed - a second request is sent to actually create the link
        $response2 = $this->actingAs($this->user)->post(route('links.data.store'), $data);

        $response2->assertSuccessful();
        $response2->assertJsonStructure(['link', 'name']);
    }

    //  Test Submitting a link without a file attached
    public function test_submit_link_no_file()
    {
        $data = [
            'linkCustomer' => 'on',
            'customerID'   => $this->customer[0]->cust_id,
            'name'         => 'Test File Link',
            'allow_up'     => 'on',
            'expire'       => date('Y-m-d', strtotime('+30 days')),
            '_completed'   => true
        ];
        $response = $this->actingAs($this->user)->post(route('links.data.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['link', 'name']);
    }

    //  Test submitting a link without a customer selected
    public function test_submit_link_no_customer()
    {
        $data = [
            'name'       => 'Test File Link',
            'allow_up'   => 'on',
            'expire'     => date('Y-m-d', strtotime('+30 days')),
            'file'       => Null,
            '_completed' => true
        ];
        $response = $this->actingAs($this->user)->post(route('links.data.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['link', 'name']);
    }

    //  Test No Name validation error
    public function test_submit_link_no_name()
    {
        $user = $this->getTech();
        $data = [
            'allow_up'   => 'on',
            'expire'     => date('Y-m-d', strtotime('+30 days')),
            'file'       => Null,
            '_completed' => true
        ];
        $response = $this->actingAs($user)->post(route('links.data.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    //  Test no Expiration Date validation error
    public function test_submit_link_no_expire_date()
    {
        $user = $this->getTech();
        $data = [
            'name'       => 'Test File Link',
            'file'       => Null,
            '_completed' => true
        ];
        $response = $this->actingAs($user)->post(route('links.data.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('expire');
    }

    //  Test addinf file link with instructions included
    public function test_submit_link_with_instructions()
    {
        // $faker = Faker::create();
        $data = [
            'name'            => 'Test File Link',
            'allow_up'        => 'on',
            'expire'          => date('Y-m-d', strtotime('+30 days')),
            'file'            => Null,
            '_completed'      => true,
            'addInstructions' => 'on',
            'note'            => 'This is a random string with instrctions.'
        ];
        $response = $this->actingAs($this->user)->post(route('links.data.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['link', 'name']);
    }
}
