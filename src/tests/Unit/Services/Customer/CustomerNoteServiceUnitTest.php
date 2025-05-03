<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Services\Customer\CustomerNoteService;
use Tests\TestCase;

class CustomerNoteServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createCustomerNote()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'details' => 'This is the notes details',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(collect($data), $customer, $user);

        $this->assertEquals($res->subject, $data['subject']);

        $this->assertDatabaseHas('customer_notes', [
            'subject' => $data['subject'],
            'cust_id' => $customer->cust_id,
        ]);
    }

    public function test_create_customer_note_site_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3), 'sites')
            ->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => $customer->Sites
                ->pluck('cust_site_id')
                ->toArray(),
            'details' => 'This is the notes details',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(collect($data), $customer, $user);

        $this->assertEquals($res->subject, $data['subject']);

        $this->assertDatabaseHas('customer_notes', [
            'subject' => $data['subject'],
            'cust_id' => $customer->cust_id,
        ]);

        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->Sites[0]->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->Sites[1]->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->Sites[2]->cust_site_id,
        ]);
    }

    public function test_create_customer_note_equipment_note(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()
            ->has(CustomerEquipment::factory(), 'equipment')
            ->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'equipment',
            'urgent' => true,
            'site_list' => [],
            'details' => 'This is the notes details',
            'cust_equip_id' => $customer->Equipment[0]->cust_equip_id,
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(collect($data), $customer, $user);

        $this->assertEquals($res->subject, $data['subject']);

        $this->assertDatabaseHas('customer_notes', [
            'subject' => $data['subject'],
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $customer->Equipment[0]->cust_equip_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerNote();
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_note(): void
    {
        $user = User::factory()->createQuietly();
        $note = CustomerNote::factory()->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->updateCustomerNote(collect($data), $note, $user);

        $this->assertEquals($res->subject, $data['subject']);

        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $note->note_id,
            'subject' => $data['subject'],
        ]);
    }

    public function test_update_customer_note_site_modification(): void
    {
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3), 'sites')
            ->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $note->Sites()->sync([
            $customer->Sites[0]->cust_site_id,
            $customer->Sites[1]->cust_site_id,
        ]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => [
                $customer->Sites[1]->cust_site_id,
                $customer->Sites[2]->cust_site_id,
            ],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->updateCustomerNote(collect($data), $note, $user);

        $this->assertEquals($res->subject, $data['subject']);

        $this->assertDatabaseHas('customer_notes', [
            'note_id' => $note->note_id,
            'subject' => $data['subject'],
        ]);

        $this->assertDatabaseMissing('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->Sites[0]->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->Sites[1]->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_site_notes', [
            'note_id' => $note->note_id,
            'cust_site_id' => $customer->Sites[2]->cust_site_id,
        ]);
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

        $this->assertSoftDeleted('customer_notes', [
            'note_id' => $note->note_id,
        ]);
    }

    public function test_destroy_customer_note_force(): void
    {
        $note = CustomerNote::factory()->create();

        $testObj = new CustomerNoteService;
        $testObj->destroyCustomerNote($note, true);

        $this->assertDatabaseMissing('customer_notes', [
            'note_id' => $note->note_id,
        ]);
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
