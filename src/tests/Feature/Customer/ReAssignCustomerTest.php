<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use Tests\TestCase;

class ReAssignCustomerTest extends TestCase
{
    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $response = $this->get(route('customers.re-assign.edit'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.re-assign.edit'));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('customers.re-assign.edit'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'moveSiteId' => 1,
            'toCustomer' => 2,
        ];

        $response = $this->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'moveSiteId' => 1,
            'toCustomer' => 2,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(403);
    }

    public function test_update_solo_customer()
    {
        $fromCust = Customer::factory()
            ->has(CustomerEquipment::factory())
            ->has(CustomerNote::factory())
            ->has(CustomerContact::factory())
            ->has(CustomerFile::factory())
            ->create();
        $toCust = Customer::factory()->create();

        $fromCust->CustomerEquipment[0]
            ->CustomerSite()
            ->attach($fromCust->primary_site_id);
        $fromCust->CustomerContact[0]
            ->CustomerSite()
            ->attach($fromCust->primary_site_id);

        $noteId = $fromCust->CustomerNote[0]->note_id;
        $fileId = $fromCust->CustomerFile[0]->cust_file_id;

        $data = [
            'moveSiteId' => $fromCust->primary_site_id,
            'toCustomer' => $toCust->cust_id,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(302);

        $this->assertSoftDeleted($fromCust);
        $this->assertDatabaseMissing('customers', [
            'cust_id' => $fromCust->cust_id,
            'primary_site_id' => $data['moveSiteId'],
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $data['moveSiteId'],
            'cust_id' => $data['toCustomer'],
        ]);
        $this->assertDatabaseHas('customer_equipment', [
            'cust_equip_id' => $fromCust->CustomerEquipment[0]->cust_equip_id,
            'cust_id' => $toCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $fromCust->CustomerContact[0]->cont_id,
            'cust_id' => $toCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $noteId,
            'cust_id' => $toCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_files', [
            'cust_file_id' => $fileId,
            'cust_id' => $toCust->cust_id,
        ]);
    }

    public function test_update_with_equipment()
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4))
            ->create();
        $movingSite = CustomerSite::factory()
            ->create(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->create();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Create two Equipment Types
        $equip = CustomerEquipment::factory()
            ->count(2)
            ->create(['cust_id' => $fromCust->cust_id]);
        $equip[0]->CustomerSite()->sync($siteArray);
        $equip[1]->CustomerSite()->attach($movingSite->cust_site_id);

        // Add Some Customer Notes to the equipment
        $notes = [
            CustomerNote::factory()
                ->create([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[0]->cust_equip_id,
                ]),
            CustomerNote::factory()
                ->create([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[1]->cust_equip_id,
                ]),
        ];

        // Add Some Customer Files to the equipment
        $files = [
            CustomerFile::factory()
                ->create([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[0]->cust_equip_id,
                ]),

            CustomerFile::factory()
                ->create([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[1]->cust_equip_id,
                ]),
        ];

        $data = [
            'moveSiteId' => $movingSite->cust_site_id,
            'toCustomer' => $toCust->cust_id,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('customer_sites', [
            'cust_id' => $toCust->cust_id,
            'cust_site_id' => $movingSite->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_equipment', [
            'cust_equip_id' => $equip[0]->cust_equip_id,
            'cust_id' => $fromCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_equipment', [
            'cust_equip_id' => $equip[1]->cust_equip_id,
            'cust_id' => $toCust->cust_id,
        ]);

        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $notes[0]->note_id,
            'cust_id' => $fromCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $notes[1]->note_id,
            'cust_id' => $toCust->cust_id,
        ]);

        $this->assertDatabaseHas('customer_files', [
            'cust_file_id' => $files[0]->cust_file_id,
            'cust_id' => $fromCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_files', [
            'cust_file_id' => $files[1]->cust_file_id,
            'cust_id' => $toCust->cust_id,
        ]);
    }

    public function test_update_with_contacts()
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4))
            ->has(CustomerContact::factory()->count(3))
            ->create();
        $movingSite = CustomerSite::factory()
            ->create(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->create();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Assign the contacts to sites
        $fromCust->CustomerContact[0]->CustomerSite()->sync($siteArray);
        $fromCust->CustomerContact[1]->CustomerSite()->attach($movingSite->cust_site_id);
        $fromCust->CustomerContact[2]->CustomerSite()->sync($siteArray);
        $fromCust->CustomerContact[2]->CustomerSite()->detach($movingSite->cust_site_id);

        $contIdList = [
            $fromCust->CustomerContact[0]->cont_id,
            $fromCust->CustomerContact[1]->cont_id,
            $fromCust->CustomerContact[2]->cont_id,
        ];

        $data = [
            'moveSiteId' => $movingSite->cust_site_id,
            'toCustomer' => $toCust->cust_id,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('customer_sites', [
            'cust_id' => $toCust->cust_id,
            'cust_site_id' => $movingSite->cust_site_id,
        ]);

        $this->assertDatabaseMissing('customer_site_contacts', [
            'cont_id' => $contIdList[0],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_contacts', [
            'cont_id' => $contIdList[1],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
        $this->assertDatabaseMissing('customer_site_contacts', [
            'cont_id' => $contIdList[2],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
    }

    public function test_update_with_notes()
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4))
            ->has(CustomerNote::factory()->count(3))
            ->create();
        $movingSite = CustomerSite::factory()
            ->create(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->create();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Assign the contacts to sites
        $fromCust->CustomerNote[0]->CustomerSite()->sync($siteArray);
        $fromCust->CustomerNote[1]->CustomerSite()->attach($movingSite->cust_site_id);

        $noteIdList = [
            $fromCust->CustomerNote[0]->note_id,
            $fromCust->CustomerNote[1]->note_id,
            $fromCust->CustomerNote[2]->note_id,
        ];

        $data = [
            'moveSiteId' => $movingSite->cust_site_id,
            'toCustomer' => $toCust->cust_id,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('customer_sites', [
            'cust_id' => $toCust->cust_id,
            'cust_site_id' => $movingSite->cust_site_id,
        ]);

        $this->assertDatabaseMissing('customer_site_notes', [
            'note_id' => $noteIdList[0],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $noteIdList[1],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
        $this->assertDatabaseMissing('customer_site_notes', [
            'note_id' => $noteIdList[2],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
    }

    public function test_update_with_files()
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4))
            ->has(CustomerFile::factory()->count(3))
            ->create();
        $movingSite = CustomerSite::factory()
            ->create(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->create();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Assign the contacts to sites
        $fromCust->CustomerFile[0]->CustomerSite()->sync($siteArray);
        $fromCust->CustomerFile[1]->CustomerSite()->attach($movingSite->cust_site_id);

        $fileIdList = [
            $fromCust->CustomerFile[0]->cust_file_id,
            $fromCust->CustomerFile[1]->cust_file_id,
            $fromCust->CustomerFile[2]->cust_file_id,
        ];

        $data = [
            'moveSiteId' => $movingSite->cust_site_id,
            'toCustomer' => $toCust->cust_id,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('customers.re-assign.update'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('customer_sites', [
            'cust_id' => $toCust->cust_id,
            'cust_site_id' => $movingSite->cust_site_id,
        ]);

        $this->assertDatabaseMissing('customer_site_files', [
            'cust_file_id' => $fileIdList[0],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_files', [
            'cust_file_id' => $fileIdList[1],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
        $this->assertDatabaseMissing('customer_site_files', [
            'cust_file_id' => $fileIdList[2],
            'cust_site_id' => $movingSite->cust_site_id,
        ]);
    }
}