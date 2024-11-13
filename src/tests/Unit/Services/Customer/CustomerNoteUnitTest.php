<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Services\Customer\CustomerNoteService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerNoteUnitTest extends TestCase
{
    use WithFaker;

    /*
    |---------------------------------------------------------------------------
    | createCustomerNote()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer_general_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'subject' => 'Note Subject',
            'note_type' => 'general',
            'urgent' => false,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'Some Note Details...',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(collect($data), $customer, $user);

        $this->assertEquals(
            $res->only(['subject', 'urgent', 'details']),
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        $this->assertEquals($res->created_by, $user->user_id);

        $this->assertDatabaseHas(
            'customer_notes',
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );
    }

    public function test_create_customer_site_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerSite::factory(5))
            ->create();
        $data = [
            'subject' => 'Note Subject',
            'note_type' => 'site',
            'urgent' => false,
            'site_list' => $customer->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
            'cust_equip_id' => null,
            'details' => 'Some Note Details...',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(collect($data), $customer, $user);

        $this->assertEquals(
            $res->only(['subject', 'urgent', 'details']),
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        $this->assertDatabaseHas(
            'customer_notes',
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        foreach ($customer->CustomerSite as $site) {
            $this->assertDatabaseHas('customer_site_notes', [
                'note_id' => $res->note_id,
                'cust_site_id' => $site->cust_site_id,
            ]);
        }
    }

    public function test_create_customer_equipment_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerEquipment::factory())
            ->create();
        $data = [
            'subject' => 'Note Subject',
            'note_type' => 'equipment',
            'urgent' => false,
            'site_list' => [],
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'details' => 'Some Note Details...',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(collect($data), $customer, $user);

        $this->assertEquals(
            $res->only(['subject', 'urgent', 'details']),
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        $this->assertDatabaseHas(
            'customer_notes',
            collect($data)
                ->only(['subject', 'urgent', 'details', 'cust_equip_id'])
                ->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerNote()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_general_note(): void
    {
        $user = User::factory()->create();
        $note = CustomerNote::factory()->create();
        $data = [
            'subject' => 'Note Subject',
            'note_type' => 'general',
            'urgent' => false,
            'site_list' => [],
            'cust_equip_id' => null,
            'details' => 'Some Note Details...',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->updateCustomerNote(collect($data), $note, $user);

        $this->assertEquals(
            $res->only(['subject', 'urgent', 'details']),
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        $this->assertEquals($res->updated_by, $user->user_id);

        $this->assertDatabaseHas(
            'customer_notes',
            collect($data)
                ->only(['subject', 'urgent', 'details', 'cust_equip_id'])
                ->toArray()
        );
    }

    public function test_update_customer_site_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerSite::factory(5))
            ->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'Note Subject',
            'note_type' => 'site',
            'urgent' => false,
            'site_list' => $customer->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
            'cust_equip_id' => null,
            'details' => 'Some Note Details...',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->updateCustomerNote(collect($data), $note, $user);

        $this->assertEquals(
            $res->only(['subject', 'urgent', 'details']),
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        $this->assertEquals($res->updated_by, $user->user_id);

        $this->assertDatabaseHas(
            'customer_notes',
            collect($data)
                ->only(['subject', 'urgent', 'details', 'cust_equip_id'])
                ->toArray()
        );

        foreach ($customer->CustomerSite as $site) {
            $this->assertDatabaseHas('customer_site_notes', [
                'note_id' => $res->note_id,
                'cust_site_id' => $site->cust_site_id,
            ]);
        }
    }

    public function test_update_customer_equipment_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerEquipment::factory())
            ->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'Note Subject',
            'note_type' => 'equipment',
            'urgent' => false,
            'site_list' => [],
            'cust_equip_id' => $customer->CustomerEquipment[0]->cust_equip_id,
            'details' => 'Some Note Details...',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->updateCustomerNote(collect($data), $note, $user);

        $this->assertEquals(
            $res->only(['subject', 'urgent', 'details']),
            collect($data)->only(['subject', 'urgent', 'details'])->toArray()
        );

        $this->assertEquals($res->updated_by, $user->user_id);

        $this->assertDatabaseHas(
            'customer_notes',
            collect($data)
                ->only(['subject', 'urgent', 'details', 'cust_equip_id'])
                ->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomerNote()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_note(): void
    {
        $note = CustomerNote::factory()->create();

        $testObj = new CustomerNoteService;
        $testObj->destroyCustomerNote($note);

        $this->assertSoftDeleted(
            'customer_notes',
            $note->only(['note_id', 'subject'])
        );
    }

    public function test_destroy_customer_note_force(): void
    {
        $note = CustomerNote::factory()->create();
        $note->delete();

        $testObj = new CustomerNoteService;
        $testObj->destroyCustomerNote($note, true);

        $this->assertDatabaseMissing(
            'customer_notes',
            $note->only(['note_id', 'subject'])
        );
    }

    /*
    |---------------------------------------------------------------------------
    | restoreCustomerNote()
    |---------------------------------------------------------------------------
    */
    public function test_restore_customer_note(): void
    {
        $note = CustomerNote::factory()->create();
        $note->delete();

        $testObj = new CustomerNoteService;
        $testObj->restoreCustomerNote($note);

        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $note->note_id,
            'deleted_at' => null,
        ]);
    }
}
