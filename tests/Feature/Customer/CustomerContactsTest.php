<?php

namespace Tests\Feature;

use App\Customers;
use Tests\TestCase;
use App\CustomerContacts;

class CustomerContactsTest extends TestCase
{
    protected $cust, $childCust, $cont, $childCont;

    public function setUp():void
    {
        Parent::setup();

        $this->cust = factory(Customers::class)->create();
        $this->childCust = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $this->cont = factory(CustomerContacts::class, 2)->create([
            'cust_id' => $this->cust->cust_id,
            'shared'  => 1,
        ]);
        $this->childCont = factory(CustomerContacts::class, 2)->create([
            'cust_id' => $this->childCust->cust_id,
            'shared'  => 0,
        ]);
    }

    //  Test getting contacts as guest
    public function test_get_contacts_as_guest()
    {
        $response = $this->get(route('customer.contacts.show', $this->cust->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test Getting contacts as tech
    public function test_get_contacts()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.contacts.show', $this->cust->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['cont_id', 'cust_id', 'name', 'customer_contact_phones', 'email']]);
    }

    //  Test Getting contacts from the child customer as tech
    public function test_get_contacts_from_child()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.contacts.show', $this->childCust->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['cont_id', 'cust_id', 'name', 'customer_contact_phones', 'email']]);
    }

    //  Test adding contact as a guest
    public function test_add_contact_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name' => 'Mickey Mouse',
            'email' => 'mickey@mouse.email',
        ];

        $response = $this->post(route('customer.contacts.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test adding contact as tech
    public function test_add_contact()
    {
        $user = $this->getTech();
        $data = [
            'cust_id'  => $this->cust->cust_id,
            'name'    => 'Mickey Mouse',
            'email'   => 'mickey@mouse.email',
            'numbers' => [
                'type'   => [2],
                //  TODO:  fix this to pull a proper number from DB
                'number' => [5306654744],
                'ext'    => null
            ]
        ];

        $response = $this->actingAs($user)->post(route('customer.contacts.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test adding contact to the child customer as tech
    public function test_add_contact_to_child()
    {
        $user = $this->getTech();
        $data = [
            'cust_id'  => $this->childCust->cust_id,
            'shared'  => 'true',
            'name'    => 'Mickey Mouse',
            'email'   => 'mickey@mouse.email',
            'numbers' => [
                'type'   => [2],
                //  TODO:  fix this to pull a proper number from DB
                'number' => [5306654744],
                'ext'    => null
            ]
        ];

        $response = $this->actingAs($user)->post(route('customer.contacts.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test updating contact as guest
    public function test_update_contact_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name' => 'Jimmy Mouse',
            'email' => 'jimmy@mouse.email',
        ];

        $response = $this->put(route('customer.contacts.update', $this->cont[0]->cont_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test updating contact as Tech
    public function test_update_contact()
    {
        $user = $this->getTech();
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name'   => 'Jimmy Mouse',
            'email'  => 'jimmy@mouse.email',
            'numbers' => [
                'type'   => [2],
                'number' => [5306654744],
                'ext'    => null
            ]
        ];

        $response = $this->actingAs($user)->put(route('customer.contacts.update', $this->cont[0]->cont_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test updating customer child contact as Tech
    public function test_update_child_contact()
    {
        $user = $this->getTech();
        $data = [
            'cust_id' => $this->childCust->cust_id,
            'name'   => 'Jimmy Mouse',
            'email'  => 'jimmy@mouse.email',
            'numbers' => [
                'type'   => [2],
                'number' => [5306654744],
                'ext'    => null
            ]
        ];

        $response = $this->actingAs($user)->put(route('customer.contacts.update', $this->childCont[0]->cont_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);

        //  TODO:  Get the contact from the database and verify it still belongs to the child customer and is not shared
    }

    //  Test updating customer child contact as Tech and make it shared to parent
    public function test_update_child_contact_make_shared()
    {
        $user = $this->getTech();
        $data = [
            'cust_id' => $this->childCust->cust_id,
            'shared'  => 'true',
            'name'   => 'Jimmy Mouse',
            'email'  => 'jimmy@mouse.email',
            'numbers' => [
                'type'   => [2],
                'number' => [5306654744],
                'ext'    => null
            ]
        ];

        $response = $this->actingAs($user)->put(route('customer.contacts.update', $this->childCont[0]->cont_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);

        //  TODO:  get the contact from the database and verify it has been moved to the parent
    }

    //  Test deleting contact as Guest
    public function test_delete_contact_as_guest()
    {
        $response = $this->delete(route('customer.contacts.destroy', $this->cont[0]->cont_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test deleting contact as Tech
    public function test_delete_contact()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('customer.contacts.destroy', $this->cont[0]->cont_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Try to download a contact as VCard as a guest
    public function test_download_contact_as_guest()
    {
        $response = $this->get(route('customer.contacts.edit', $this->cont[1]->cont_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to downlaod a contact as VCard
    // public function test_download_contact()
    // {
    //     $user = $this->getTech();
    //     $response = $this->actingAs($user)->get(route('customer.contacts.edit', $this->cont[1]->cont_id));
        //  TODO - test vcard download
    //     $response->assertSuccessful();
    // }
}
