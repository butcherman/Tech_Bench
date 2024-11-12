<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use App\Services\Customer\CustomerNoteService;
use Tests\TestCase;

class CustomerNoteUnitTest extends TestCase
{
    public function test_create_customer_note(): void
    {
        $customer = Customer::factory()
            ->has(CustomerSite::factory()->count(3))
            ->create();
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'site',
            'urgent' => true,
            'site_list' => $customer->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
            'details' => 'This is the notes details',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->createCustomerNote(
            collect($data),
            $customer,
            User::factory()->create()
        );

        $this->assertEquals($res->subject, $data['subject']);

        unset($data['site_list']);
        unset($data['note_type']);
        unset($data['note_id']);

        $this->assertDatabaseHas('customer_notes', $data);
        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->CustomerSite[0]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->CustomerSite[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_notes', [
            'cust_site_id' => $customer->CustomerSite[2]->cust_site_id,
        ]);
    }

    public function test_update_customer_note(): void
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'subject' => 'This is a test Note',
            'note_type' => 'general',
            'urgent' => true,
            'site_list' => [],
            'note_id' => null,
            'details' => 'This is the notes details',
        ];

        $testObj = new CustomerNoteService;
        $res = $testObj->updateCustomerNote(
            collect($data),
            $note,
            User::factory()->create()
        );

        $this->assertEquals($res->subject, $data['subject']);

        unset($data['site_list']);
        unset($data['note_type']);
        $data['note_id'] = $note->note_id;

        $this->assertDatabaseHas('customer_notes', $data);
    }

    public function test_destroy_customer_note(): void
    {
        $note = CustomerNote::factory()->create();

        $testObj = new CustomerNoteService;
        $testObj->destroyCustomerNote($note);

        $this->assertSoftDeleted('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));
    }

    public function test_destroy_customer_note_force(): void
    {
        $note = CustomerNote::factory()->create();

        $testObj = new CustomerNoteService;
        $testObj->destroyCustomerNote($note, true);

        $this->assertDatabaseMissing('customer_notes', $note->only([
            'note_id',
            'subject',
            'details',
        ]));
    }

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
