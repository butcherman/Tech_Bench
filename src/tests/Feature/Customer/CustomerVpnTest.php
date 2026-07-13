<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerVpn;
use App\Models\User;
use Tests\TestCase;

class CustomerVpnTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        config(['customer.allow_vpn_data' => true]);

        $customer = Customer::factory()->create();
        $data = [
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ];

        $response = $this->post(route('customers.vpn-data.store', $customer->slug), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        config(['customer.allow_vpn_data' => true]);

        $this->changeRolePermission(4, 'Add Customer Equipment');

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.vpn-data.store', $customer->slug), $data);

        $response->assertForbidden();
    }

    public function test_store_feature_disabled(): void
    {
        config(['customer.allow_vpn_data' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.vpn-data.store', $customer->slug), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        config(['customer.allow_vpn_data' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.vpn-data.store', $customer->slug), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'VPN Data Saved');

        $this->assertDatabaseHas('customer_vpns', $data);

        $this->assertDatabaseMissing('customers', [
            'cust_id' => $customer->cust_id,
            'vpn_id' => null,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        config(['customer.allow_vpn_data' => true]);

        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $data = [
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ];

        $response = $this->put(route('customers.vpn-data.update', [
            $customer->slug,
            $vpnData->vpn_id,
        ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        config(['customer.allow_vpn_data' => true]);

        $this->changeRolePermission(4, 'Edit Customer Equipment');

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $data = [
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.vpn-data.update', [
                $customer->slug,
                $vpnData->vpn_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update_feature_disabled(): void
    {
        config(['customer.allow_vpn_data' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $data = [
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.vpn-data.update', [
                $customer->slug,
                $vpnData->vpn_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        config(['customer.allow_vpn_data' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $data = [
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.vpn-data.update', [
                $customer->slug,
                $vpnData->vpn_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'VPN Data Updated');

        $this->assertDatabaseHas('customer_vpns', [
            'vpn_id' => $vpnData->vpn_id,
            'vpn_client_name' => 'renamed client',
            'vpn_portal_url' => 'vpn.portal.random',
            'vpn_username' => 'usernameIsMy',
            'vpn_password' => 'passwordIsMy',
            'notes' => 'This is a test account',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        config(['customer.allow_vpn_data' => true]);

        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $response = $this->delete(route('customers.vpn-data.destroy', [
            $customer->slug,
            $vpnData->vpn_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        config(['customer.allow_vpn_data' => true]);

        $this->changeRolePermission(4, 'Delete Customer Equipment');

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $response = $this->actingAs($user)
            ->delete(route('customers.vpn-data.destroy', [
                $customer->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertForbidden();
    }

    public function test_destroy_feature_disabled(): void
    {
        config(['customer.allow_vpn_data' => false]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $response = $this->actingAs($user)
            ->delete(route('customers.vpn-data.destroy', [
                $customer->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        config(['customer.allow_vpn_data' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer->vpn_id = $vpnData->vpn_id;
        $customer->save();

        $response = $this->actingAs($user)
            ->delete(route('customers.vpn-data.destroy', [
                $customer->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'VPN Data Deleted');

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'vpn_id' => null,
        ]);

        $this->assertDatabaseMissing('customer_vpns', $vpnData->toArray());
    }

    public function test_destroy_in_use(): void
    {
        config(['customer.allow_vpn_data' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $customer1->vpn_id = $vpnData->vpn_id;
        $customer1->save();

        $customer2->vpn_id = $vpnData->vpn_id;
        $customer2->save();

        $response = $this->actingAs($user)
            ->delete(route('customers.vpn-data.destroy', [
                $customer1->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'VPN Data Deleted');

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer1->cust_id,
            'vpn_id' => null,
        ]);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer2->cust_id,
            'vpn_id' => $vpnData->vpn_id,
        ]);

        $this->assertDatabaseHas('customer_vpns', $vpnData->toArray());
    }
}
