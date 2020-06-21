<?php

namespace Tests\Feature\Customers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Customers;
use App\CustomerNotes;

class CustomerNotesControllerTest extends TestCase
{
    public function test_store()
    {
        $cust = factory(Customers::class)->create();
        $note = factory(CustomerNotes::class)->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ];

        $response = $this->actingAs($this->getTech())->post(route('customer.notes.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_store_guest()
    {
        $cust = factory(Customers::class)->create();
        $note = factory(CustomerNotes::class)->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ];

        $response = $this->post(route('customer.notes.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $cust = factory(Customers::class)->create();
        factory(CustomerNotes::class, 5)->create(['cust_id' => $cust->cust_id]);

        $response = $this->actingAs($this->getTech())->get(route('customer.notes.show', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJsonStructure([['note_id', 'cust_id', 'user_id', 'urgent', 'shared', 'subject', 'description']]);
    }

    public function test_show_guets()
    {
        $cust = factory(Customers::class)->create();
        factory(CustomerNotes::class, 5)->create(['cust_id' => $cust->cust_id]);

        $response = $this->get(route('customer.notes.show', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $cust     = factory(Customers::class)->create();
        $existing = factory(CustomerNotes::class)->create(['cust_id' => $cust->cust_id]);
        $note     = factory(CustomerNotes::class)->make();
        $data     = [
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ];

        $response = $this->actingAs($this->getTech())->put(route('customer.notes.update', $existing->note_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_update_guest()
    {
        $cust     = factory(Customers::class)->create();
        $existing = factory(CustomerNotes::class)->create(['cust_id' => $cust->cust_id]);
        $note     = factory(CustomerNotes::class)->make();
        $data     = [
            'cust_id' => $cust->cust_id,
            'shared'  => true,
            'urgent'  => $note->urgent,
            'subject' => $note->subject,
            'description' => $note->description,
        ];

        $response = $this->put(route('customer.notes.update', $existing->note_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy()
    {
        $note = factory(CustomerNotes::class)->create();

        $response = $this->actingAs($this->getTech())->delete(route('customer.notes.destroy', $note->note_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_destroy_guest()
    {
        $note = factory(CustomerNotes::class)->create();

        $response = $this->delete(route('customer.notes.destroy', $note->note_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
