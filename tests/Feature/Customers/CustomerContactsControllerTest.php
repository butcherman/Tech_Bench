<?php

namespace Tests\Feature\Customers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Customers;
use App\CustomerContactPhones;
use App\CustomerContacts;

class CustomerContactsControllerTest extends TestCase
{
    public function test_index_guest()
    {
        $response = $this->get(route('customer.contacts.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs($this->getTech())->get(route('customer.contacts.index'));

        $response->assertSuccessful();
        $response->assertJsonStructure([['phone_type_id', 'description', 'icon_class']]);
    }

    public function test_store_guest()
    {
        $cust = factory(Customers::class)->create();
        $cont = factory(CustomerContacts::class)->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
            'customer_contact_phones' => [[
                'phone_type_id' => 1,
                'readable'      => '(541) 555-1212',
                'extension'     => 123,
            ]]
        ];

        $response = $this->post(route('customer.contacts.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store()
    {
        $cust = factory(Customers::class)->create();
        $cont = factory(CustomerContacts::class)->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
            'customer_contact_phones' => [[
                'phone_type_id' => 1,
                'readable'      => '(541) 555-1212',
                'extension'     => 123,
            ]]
        ];

        $response = $this->actingAs($this->getTech())->post(route('customer.contacts.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_show_guest()
    {
        $cust   = factory(Customers::class)->create();
        $cont   = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont->cont_id]);

        $response = $this->get(route('customer.contacts.show', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $cust   = factory(Customers::class)->create();
        $cont   = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont->cont_id]);

        $response = $this->actingAs($this->getTech())->get(route('customer.contacts.show', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJsonStructure([['cont_id', 'cust_id', 'shared', 'name', 'email', 'customer_contact_phones']]);
    }

    // public function test_download_guest()
    // {

    // }

    // public function test_download()
    // {
    //     $cont = factory(CustomerContacts::class)->create();

    //     $response = $this->actingAs($this->getTech())->get(route('customer.contacts.download', $cont->cont_id));
    //     $response->assertSuccessful();
    // }

    public function test_update_guest()
    {
        $cust = factory(Customers::class)->create();
        $cont = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        $phone = factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => 'New Name',
            'email'   => 'newEm@em.com',
            'shared'  => false,
            'customer_contact_phones' => [
                [
                    'phone_type_id' => 1,
                    'readable'      => '(541) 555-1212',
                    'extension'     => 123,
                ],
                [
                    'id'            => $phone[0]->id,
                    'phone_type_id' => 2,
                    'readable'      => '(816) 444-2121',
                    'extension'     => null,
                ]
            ],
        ];

        $response = $this->put(route('customer.contacts.update', $cont->cont_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $cust = factory(Customers::class)->create();
        $cont = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        $phone = factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => 'New Name',
            'email'   => 'newEm@em.com',
            'shared'  => false,
            'customer_contact_phones' => [
                [
                    'phone_type_id' => 1,
                    'readable'      => '(541) 555-1212',
                    'extension'     => 123,
                ],
                [
                    'id'            => $phone[0]->id,
                    'phone_type_id' => 2,
                    'readable'      => '(816) 444-2121',
                    'extension'     => null,
                ]
            ],
        ];

        $response = $this->actingAs($this->getTech())->put(route('customer.contacts.update', $cont->cont_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_destroy_guest()
    {
        $cont = factory(CustomerContacts::class)->create();

        $response = $this->delete(route('customer.contacts.destroy', $cont->cont_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy()
    {
        $cont = factory(CustomerContacts::class)->create();

        $response = $this->actingAs($this->getTech())->delete(route('customer.contacts.destroy', $cont->cont_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
