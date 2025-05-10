<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\CustomerSite;
use App\Models\User;
use Tests\TestCase;

class CustomerContactTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $cust = Customer::factory()->createQuietly();
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
                ],
            ],
        ];

        $response = $this->post(
            route('customers.contacts.store', $cust->slug),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();
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
                ],
            ],
        ];

        $this->changeRolePermission(4, 'Add Customer Contact');

        $response = $this->actingAs($user)
            ->post(route('customers.contacts.store', $cust->slug), $data);
        $response->assertStatus(403);
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()
            ->has(CustomerSite::factory()->count(5), 'sites')
            ->createQuietly();
        $cont = CustomerContact::factory()->make();
        $data = [
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'site_list' => $cust->Sites
                ->pluck('cust_site_id')
                ->toArray(),
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-1212',
                    'ext' => '232',
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.contacts.store', $cust->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.contact.created', [
                'cont' => $data['name'],
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
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $cust = Customer::factory()->createQuietly();
        $cont = CustomerContact::factory()->createQuietly();
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
                ],
            ],
        ];

        $response = $this->put(
            route('customers.contacts.update', [$cust->slug, $cont->cont_id]),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();
        $cont = CustomerContact::factory()
            ->createQuietly(['cust_id' => $cust->cust_id]);
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
                ],
            ],
        ];

        $this->changeRolePermission(4, 'Edit Customer Contact');

        $response = $this->actingAs($user)
            ->put(
                route('customers.contacts.update', [
                    $cust->slug,
                    $cont->cont_id,
                ]),
                $data
            );

        $response->assertStatus(403);
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cust = Customer::factory()->createQuietly();
        $site = CustomerSite::factory()
            ->count(5)
            ->create(['cust_id' => $cust->cust_id]);
        $cont = CustomerContact::factory()->createQuietly([
            'cust_id' => $cust->cust_id,
        ]);
        $cont->Sites()
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
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(
                route('customers.contacts.update', [
                    $cust->slug,
                    $cont->cont_id,
                ]),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.contact.updated', [
                'cont' => $data['name'],
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
    }

    public function test_update_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $invalid = Customer::factory()->create();
        $cust = Customer::factory()->createQuietly();
        $site = CustomerSite::factory()
            ->count(5)
            ->create(['cust_id' => $cust->cust_id]);
        $cont = CustomerContact::factory()->createQuietly([
            'cust_id' => $cust->cust_id,
        ]);
        $cont->Sites()
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
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(
                route('customers.contacts.update', [
                    $invalid->slug,
                    $cont->cont_id,
                ]),
                $data
            );

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $cont = CustomerContact::factory()->createQuietly();

        $response = $this->delete(
            route('customers.contacts.destroy', [
                $cont->cust_id,
                $cont->cont_id,
            ])
        );
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cont = CustomerContact::factory()->createQuietly();

        $this->changeRolePermission(4, 'Delete Customer Contact');

        $response = $this->actingAs($user)
            ->delete(
                route('customers.contacts.destroy', [
                    $cont->cust_id,
                    $cont->cont_id,
                ])
            );

        $response->assertStatus(403);
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cont = CustomerContact::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(
                route('customers.contacts.destroy', [
                    $cont->cust_id,
                    $cont->cont_id,
                ])
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('cust.contact.deleted', [
                'cont' => $cont->name,
            ]));

        $this->assertSoftDeleted('customer_contacts', $cont->toArray());
    }

    public function test_destroy_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cont = CustomerContact::factory()->createQuietly();
        $invalid = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->delete(
                route('customers.contacts.destroy', [
                    $invalid->cust_id,
                    $cont->cont_id,
                ])
            );

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Restore Method
    |---------------------------------------------------------------------------
    */
    public function test_restore_guest(): void
    {
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $response = $this->get(
            route(
                'customers.deleted-items.restore.contacts',
                [
                    $cont->cust_id,
                    $cont->cont_id,
                ]
            )
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.contacts', [
                $cont->Customer->slug,
                $cont->cont_id,
            ]));

        $response->assertStatus(403);
    }

    public function test_restore(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.contacts', [
                $cont->Customer->slug,
                $cont->cont_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.contact.restored', [
                'cont' => $cont->name,
            ]));

        $this->assertDatabaseHas('customer_contacts', $cont->only(['cont_id']));
    }

    public function test_restore_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $invalid = Customer::factory()->create();
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.contacts', [
                $invalid->slug,
                $cont->cont_id,
            ]));

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Force Delete Method
    |---------------------------------------------------------------------------
    */
    public function test_force_delete_guest(): void
    {
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $response = $this->delete(
            route(
                'customers.deleted-items.force-delete.contacts',
                [
                    $cont->Customer->slug,
                    $cont->cont_id,
                ]
            )
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.contacts', [
                $cont->Customer->slug,
                $cont->cont_id,
            ]));

        $response->assertStatus(403);
    }

    public function test_force_deleted(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $cont = CustomerContact::factory()->createQuietly();
        $cont->deleteQuietly();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.contacts', [
                $cont->Customer->slug,
                $cont->cont_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas(
                'warning',
                __('cust.contact.force_deleted', [
                    'cont' => $cont->name,
                ])
            );

        $this->assertDatabaseMissing(
            'customer_contacts',
            $cont->only(['cont_id'])
        );
    }

    public function test_force_deleted_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $invalid = Customer::factory()->create();
        $cont = CustomerContact::factory()->createQuietly();
        $cont->deleteQuietly();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.contacts', [
                $invalid->slug,
                $cont->cont_id,
            ]));

        $response->assertStatus(404);
    }
}
