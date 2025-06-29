<?php

namespace Tests\Unit\Actions\Customer;

use App\Actions\Customer\ReAssignCustomerSite;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Tests\TestCase;

class ReAssignCustomerSiteUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_solo_customer(): void
    {
        $fromCust = Customer::factory()
            ->has(CustomerEquipment::factory(), 'equipment')
            ->has(CustomerNote::factory(), 'notes')
            ->has(CustomerContact::factory(), 'contacts')
            ->has(CustomerFile::factory(), 'files')
            ->createQuietly();
        $toCust = Customer::factory()->create();

        $fromSite = $fromCust->Sites[0];

        $fromCust->Equipment[0]
            ->Sites()
            ->attach($fromCust->primary_site_id);
        $fromCust->Contacts[0]
            ->Sites()
            ->attach($fromCust->primary_site_id);

        $noteId = $fromCust->Notes[0]->note_id;
        $fileId = $fromCust->Files[0]->cust_file_id;

        $testObj = new ReAssignCustomerSite;
        $testObj($fromSite, $toCust);

        $this->assertSoftDeleted($fromCust);
        $this->assertDatabaseMissing('customers', [
            'cust_id' => $fromCust->cust_id,
            'primary_site_id' => $fromSite->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'cust_id' => $toCust->cust_id,
            'cust_site_id' => $fromSite->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_equipment', [
            'cust_equip_id' => $fromCust->Equipment[0]->cust_equip_id,
            'cust_id' => $toCust->cust_id,
        ]);
        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $fromCust->Contacts[0]->cont_id,
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

    public function test_invoke_with_equipment(): void
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4), 'sites')
            ->createQuietly();
        $movingSite = CustomerSite::factory()
            ->create(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->createQuietly();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Create two Equipment Types
        $equip = CustomerEquipment::factory()
            ->count(2)
            ->createQuietly(['cust_id' => $fromCust->cust_id]);
        $equip[0]->Sites()->sync($siteArray);
        $equip[1]->Sites()->attach($movingSite->cust_site_id);

        // Add Some Customer Notes to the equipment
        $notes = [
            CustomerNote::factory()
                ->createQuietly([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[0]->cust_equip_id,
                ]),
            CustomerNote::factory()
                ->createQuietly([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[1]->cust_equip_id,
                ]),
        ];

        // Add Some Customer Files to the equipment
        $files = [
            CustomerFile::factory()
                ->createQuietly([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[0]->cust_equip_id,
                ]),

            CustomerFile::factory()
                ->createQuietly([
                    'cust_id' => $fromCust->cust_id,
                    'cust_equip_id' => $equip[1]->cust_equip_id,
                ]),
        ];

        $testObj = new ReAssignCustomerSite;
        $testObj($movingSite, $toCust);

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

    public function test_invoke_with_contacts(): void
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4), 'sites')
            ->has(CustomerContact::factory()->count(3), 'contacts')
            ->createQuietly();
        $movingSite = CustomerSite::factory()
            ->createQuietly(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->createQuietly();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Assign the contacts to sites
        $fromCust->Contacts[0]
            ->Sites()
            ->sync($siteArray);
        $fromCust->Contacts[1]
            ->Sites()
            ->attach($movingSite->cust_site_id);
        $fromCust->Contacts[2]
            ->Sites()
            ->sync($siteArray);
        $fromCust->Contacts[2]
            ->Sites()
            ->detach($movingSite->cust_site_id);

        $contIdList = [
            $fromCust->Contacts[0]->cont_id,
            $fromCust->Contacts[1]->cont_id,
            $fromCust->Contacts[2]->cont_id,
        ];

        $testObj = new ReAssignCustomerSite;
        $testObj($movingSite, $toCust);

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

    public function test_invoke_with_notes(): void
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4), 'sites')
            ->has(CustomerNote::factory()->count(3), 'notes')
            ->createQuietly();
        $movingSite = CustomerSite::factory()
            ->createQuietly(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->createQuietly();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Assign the contacts to sites
        $fromCust->Notes[0]
            ->Sites()
            ->sync($siteArray);
        $fromCust->Notes[1]
            ->Sites()
            ->attach($movingSite->cust_site_id);

        $noteIdList = [
            $fromCust->Notes[0]->note_id,
            $fromCust->Notes[1]->note_id,
            $fromCust->Notes[2]->note_id,
        ];

        $testObj = new ReAssignCustomerSite;
        $testObj($movingSite, $toCust);

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

    public function test_invoke_with_files(): void
    {
        $fromCust = Customer::factory()
            ->has(CustomerSite::factory()->count(4), 'sites')
            ->has(CustomerFile::factory()->count(3), 'files')
            ->createQuietly();
        $movingSite = CustomerSite::factory()
            ->createQuietly(['cust_id' => $fromCust->cust_id]);
        $toCust = Customer::factory()->createQuietly();

        $siteArray = CustomerSite::where('cust_id', $fromCust->cust_id)
            ->get()
            ->map(fn ($site) => $site->cust_site_id);

        // Assign the contacts to sites
        $fromCust->Files[0]
            ->Sites()
            ->sync($siteArray);
        $fromCust->Files[1]
            ->Sites()
            ->attach($movingSite->cust_site_id);

        $fileIdList = [
            $fromCust->Files[0]->cust_file_id,
            $fromCust->Files[1]->cust_file_id,
            $fromCust->Files[2]->cust_file_id,
        ];

        $testObj = new ReAssignCustomerSite;
        $testObj($movingSite, $toCust);

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
