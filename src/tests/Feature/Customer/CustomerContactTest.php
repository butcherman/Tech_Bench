<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\CustomerContactSite;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerContactTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->make();
        $data = [
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'site_list' => [],
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-1212',
                    'ext' => '232',
                ]
            ],
        ];

        $response = $this->post(
            route('customers.contacts.store', $cust->slug),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->make();
        $data = [
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'site_list' => [],
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-1212',
                    'ext' => '232',
                ]
            ],
        ];

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 14)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.contacts.store', $cust->slug), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        // Event::fake();

        $cust = Customer::factory()->hasCustomerSite(5)->create();
        $cont = CustomerContact::factory()->make();
        $data = [
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'site_list' => $cust->CustomerSite->pluck('cust_site_id')->toArray(),
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-1212',
                    'ext' => '232',
                ]
            ],
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.contacts.store', $cust->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.contact.created', [
            'cont' => $data['name']
        ]));

        $this->assertDatabaseHas('customer_contacts', [
            'cust_id' => $cust->cust_id,
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'local' => false,
            'decision_maker' => false,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number' => 2135551212,
            'extension' => 232,
        ]);

        // Event::assertDispatched(CustomerContactCreatedEvent::class);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create();
        $mod = CustomerContact::factory()->make();
        $ph = CustomerContactPhone::factory()
            ->count(2)
            ->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name' => $mod->name,
            'title' => $mod->title,
            'email' => $mod->email,
            'site_list' => [],
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'id' => $ph[0]->id,
                    'phone_number_type' => ['description' => 'Mobile'],
                    'phone_number' => $ph[0]->phone_number,
                    'extension' => null,
                ],
                [
                    'phone_number_type' => ['description' => 'Mobile'],
                    'phone_number' => '(213)555-2121',
                    'extension' => null,
                ]
            ],
        ];

        $response = $this->put(
            route('customers.contacts.update', [$cust->slug, $cont->cont_id]),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create();
        $mod = CustomerContact::factory()->make();
        $ph = CustomerContactPhone::factory()
            ->count(2)
            ->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name' => $mod->name,
            'title' => $mod->title,
            'email' => $mod->email,
            'site_list' => [],
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'id' => $ph[0]->id,
                    'type' => 'Mobile',
                    'number' => $ph[0]->phone_number,
                    'ext' => null,
                ],
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-2121',
                    'ext' => null,
                ]
            ],
        ];

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 15)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->put(
                route('customers.contacts.update', [$cust->slug, $cont->cont_id]),
                $data
            );
        $response->assertStatus(403);
    }
    public function test_update()
    {
        // Event::fake();

        $cust = Customer::factory()->create();
        $site = CustomerSite::factory()
            ->count(5)
            ->create(['cust_id' => $cust->cust_id]);
        $cont = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        $cont->CustomerSite()
            ->attach([$site[0]->cust_site_id, $site[1]->cust_site_id]);
        $mod = CustomerContact::factory()->make();
        $ph = CustomerContactPhone::factory()
            ->count(2)
            ->create(['cont_id' => $cont->cont_id]);

        $data = [
            'name' => $mod->name,
            'title' => $mod->title,
            'email' => $mod->email,
            'site_list' => [$site[1]->cust_site_id, $site[3]->cust_site_id],
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'id' => $ph[0]->id,
                    'type' => 'Mobile',
                    'number' => $ph[0]->phone_number,
                    'ext' => null,
                ],
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-2121',
                    'ext' => null,
                ]
            ],
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(
                route('customers.contacts.update', [$cust->slug, $cont->cont_id]),
                $data
            );
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.contact.updated', [
            'cont' => $data['name']
        ]));

        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_id' => $cust->cust_id,
            'name' => $mod->name,
            'title' => $mod->title,
            'email' => $mod->email,
            'local' => false,
            'decision_maker' => false,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number' => 2135552121,
            'extension' => null,
        ]);
        $this->assertDatabaseHas('customer_site_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_site_id' => $site[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_site_id' => $site[3]->cust_site_id,
        ]);
        $this->assertDatabaseMissing('customer_site_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_site_id' => $site[0]->cust_site_id,
        ]);
        $this->assertDatabaseMissing(
            'customer_contact_phones',
            $ph[1]->only(
                'id',
                'phone_number',
                'phone_type_id',
                'extension'
            )
        );

        // Event::assertDispatched(CustomerContactUpdatedEvent::class);
    }

    /******************************************************************************************************** */


    // public function test_update_bad_cont_id()
    // {
    //     $cust = Customer::factory()->create();
    //     $site = CustomerSite::factory()
    //         ->count(5)
    //         ->create(['cust_id' => $cust->cust_id]);
    //     $mod = CustomerContact::factory()->make();

    //     $data = [
    //         'cust_id' => $cust->cust_id,
    //         'name' => $mod->name,
    //         'title' => $mod->title,
    //         'email' => $mod->email,
    //         'site_list' => [$site[1]->cust_site_id, $site[3]->cust_site_id],
    //         'local' => false,
    //         'decision_maker' => false,
    //         'phones' => [],
    //     ];

    //     $response = $this->actingAs(User::factory()->create())
    //         ->put(
    //             route('customers.contacts.update', [$cust->slug, 54785521445]),
    //             $data
    //         );
    //     $response->assertSuccessful();
    //     $response->assertViewIs('error.customerResourceMissing');
    // }

    // /**
    //  * Destroy Method
    //  */
    public function test_destroy_guest()
    {
        $cont = CustomerContact::factory()->create();

        $response = $this->delete(
            route('customers.contacts.destroy', [$cont->cust_id, $cont->cont_id])
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $cont = CustomerContact::factory()->create();

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 16)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(
                route('customers.contacts.destroy', [$cont->cust_id, $cont->cont_id])
            );
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        // Event::fake();

        $cont = CustomerContact::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(
                route('customers.contacts.destroy', [$cont->cust_id, $cont->cont_id])
            );
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.contact.deleted', [
            'cont' => $cont->name
        ]));
        $this->assertSoftDeleted('customer_contacts', $cont->toArray());

        // Event::assertDispatched(CustomerContactDeletedEvent::class);
    }
}
