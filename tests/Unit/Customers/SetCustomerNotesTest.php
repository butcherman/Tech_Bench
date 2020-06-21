<?php

namespace Tests\Unit\Customers;

use App\CustomerNotes;
use App\Customers;
use App\Domains\Customers\SetCustomerNotes;
use App\Http\Requests\Customers\CustomerNoteRequest;
use Tests\TestCase;

class SetCustomerNotesTest extends TestCase
{
    public function test_create_new_note()
    {
        $cust = factory(Customers::class)->create();
        $note = factory(CustomerNotes::class)->make();
        $data = new CustomerNoteRequest([
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ]);

        $res = (new SetCustomerNotes)->createNewNote($data, $cust->cust_id, $this->getTech()->user_id);
        $this->assertTrue(($res));
        $this->assertDatabaseHas('customer_notes', ['cust_id' => $cust->cust_id, 'subject' => $note->subject, 'description' => $note->description, 'shared' => true, 'urgent' => $note->urgent]);
    }

    public function test_create_new_note_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $note   = factory(CustomerNotes::class)->make();
        $data   = new CustomerNoteRequest([
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ]);

        $res = (new SetCustomerNotes)->createNewNote($data, $cust->cust_id, $this->getTech()->user_id);
        $this->assertTrue(($res));
        $this->assertDatabaseHas('customer_notes', ['cust_id' => $parent->cust_id, 'subject' => $note->subject, 'description' => $note->description, 'shared' => true, 'urgent' => $note->urgent]);
    }

    public function test_update_note()
    {
        $cust     = factory(Customers::class)->create();
        $existing = factory(CustomerNotes::class)->create(['cust_id' => $cust->cust_id]);
        $note     = factory(CustomerNotes::class)->make();
        $data     = new CustomerNoteRequest([
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ]);

        $res = (new SetCustomerNotes)->updateNote($data, $cust->cust_id, $existing->note_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_notes', ['note_id' => $existing->note_id, 'cust_id' => $cust->cust_id, 'subject' => $note->subject, 'description' => $note->description, 'shared' => true, 'urgent' => $note->urgent]);
    }

    public function test_update_note_with_parent()
    {
        $parent   = factory(Customers::class)->create();
        $cust     = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $existing = factory(CustomerNotes::class)->create(['cust_id' => $cust->cust_id]);
        $note     = factory(CustomerNotes::class)->make();
        $data     = new CustomerNoteRequest([
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ]);

        $res = (new SetCustomerNotes)->updateNote($data, $cust->cust_id, $existing->note_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_notes', ['note_id' => $existing->note_id, 'cust_id' => $parent->cust_id, 'subject' => $note->subject, 'description' => $note->description, 'shared' => true, 'urgent' => $note->urgent]);
    }

    public function test_delete_note()
    {
        $note = factory(CustomerNotes::class)->create();

        $res = (new SetCustomerNotes)->deleteNote($note->note_id);
        $this->assertTrue($res);
        $this->assertDatabaseMissing('customer_notes', ['note_id' => $note->note_id]);
    }
}
