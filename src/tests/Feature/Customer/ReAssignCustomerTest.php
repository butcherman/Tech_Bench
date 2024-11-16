<?php

namespace Tests\Feature\Customer;

use App\Jobs\Customer\ReAssignSiteJob;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReAssignCustomerTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('customers.re-assign.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.re-assign.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('customers.re-assign.edit'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/ReAssign')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $data = [
            'moveSiteId' => 1,
            'toCustomer' => 2,
        ];

        $response = $this->put(route('customers.re-assign.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'moveSiteId' => 1,
            'toCustomer' => 2,
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.re-assign.update'), $data);

        $response->assertForbidden();
    }

    public function test_update_solo_customer(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $fromCust = Customer::factory()
            ->has(CustomerEquipment::factory())
            ->has(CustomerNote::factory())
            ->has(CustomerContact::factory())
            ->has(CustomerFile::factory())
            ->createQuietly();
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

        $response = $this->actingAs($user)
            ->put(route('customers.re-assign.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Re-Assignment job started in background.  This may take some time'
            );

        Bus::assertDispatched(ReAssignSiteJob::class);
    }
}
