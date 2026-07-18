<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerVpn;
use App\Models\User;
use Tests\TestCase;

class ShareVpnDataTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config([
            'customer.allow_vpn_data' => true,
            'customer.allow_share_vpn_data' => true,
        ]);

        $customer = Customer::factory()->create();
        $vpnData = CustomerVpn::create([
            'vpn_client_name' => 'vpn client',
            'vpn_portal_url' => 'vpn.random.portal',
            'vpn_username' => 'myUsername',
            'vpn_password' => 'myPassword',
            'notes' => 'This is a test account',
        ]);

        $response = $this->put(route('customers.vpn-data.share', [
            $customer->slug,
            $vpnData->vpn_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        config([
            'customer.allow_vpn_data' => true,
            'customer.allow_share_vpn_data' => true,
        ]);

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

        $response = $this->actingAs($user)
            ->put(route('customers.vpn-data.share', [
                $customer->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertForbidden();
    }

    public function test_invoke_feature_disabled(): void
    {
        config([
            'customer.allow_vpn_data' => true,
            'customer.allow_share_vpn_data' => false,
        ]);

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

        $response = $this->actingAs($user)
            ->put(route('customers.vpn-data.share', [
                $customer->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        config([
            'customer.allow_vpn_data' => true,
            'customer.allow_share_vpn_data' => true,
        ]);

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

        $response = $this->actingAs($user)
            ->put(route('customers.vpn-data.share', [
                $customer->slug,
                $vpnData->vpn_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('success', 'VPN Data Shared');

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'vpn_id' => $vpnData->vpn_id,
        ]);
    }
}
