<?php

namespace Tests\Feature\admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Customers;

class CustomerAdminTest extends TestCase
{
    //  Test visit the change cust id form as a guest
    public function test_visit_change_cust_id_form_as_guest()
    {
        $response = $this->get(route('admin.customerID'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit the change cust id form as a user without permissions
    public function test_visit_change_cust_id_form_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.customerID'));

        $response->assertStatus(403);
    }

    //  Test visit the change cust id for as a user with permission
    public function test_visit_change_cust_id_form_with_permission()
    {
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->get(route('admin.customerID'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.customerID');
    }

    //  Test visit the change cust id for as a user with permission
    public function test_visit_change_cust_id_form_as_installer()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.customerID'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.customerID');
    }

    //  Test submit the change cust id form as a guest
    public function test_submit_change_cust_id_form_as_guest()
    {
        $data = [
            'original_id' => factory(Customers::class)->create()->cust_id,
            'cust_id' => 53243245342
        ];
        $response = $this->post(route('admin.submitCustomerID'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test submit the change cust id form as a user without permissions
    public function test_submit_change_cust_id_form_no_permission()
    {
        $data = [
            'original_id' => factory(Customers::class)->create()->cust_id,
            'cust_id' => 53243245342
        ];
        $response = $this->actingAs($this->getTech())->post(route('admin.submitCustomerID'), $data);

        $response->assertStatus(403);
    }

    //  Test submit the change cust id for as a user with permission
    public function test_submit_change_cust_id_form_with_permission()
    {
        $customer = factory(Customers::class)->create();
        $data = [
            'original_id' => $customer->cust_id,
            'cust_id'     => 23324
        ];
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->post(route('admin.submitCustomerID'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test submit the change cust id for as a user with permission
    public function test_submit_change_cust_id_form_as_installer()
    {
        $customer = factory(Customers::class)->create();
        $data = [
            'original_id' => $customer->cust_id,
            'cust_id'     => 3423
        ];
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->post(route('admin.submitCustomerID'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test visit the change cust id form as a guest
    public function test_view_file_types_form_as_guest()
    {
        $response = $this->get(route('admin.custFileTypes'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit the change cust id form as a user without permissions
    public function test_view_file_types_form_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.custFileTypes'));

        $response->assertStatus(403);
    }

    //  Test visit the change cust id for as a user with permission
    public function test_view_file_types_form_with_permission()
    {
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->get(route('admin.custFileTypes'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.customerFileTypes');
    }

    //  Test visit the change cust id for as a user with permission
    public function test_view_file_types_form_as_installer()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.custFileTypes'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.customerFileTypes');
    }

    //  Test get file types as a guest
    public function test_get_file_types_as_guest()
    {
        $response = $this->get(route('admin.getCustFileTypes'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test get file types as a user without permissions
    public function test_get_file_types_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.getCustFileTypes'));

        $response->assertStatus(403);
    }

    //  Test get file typesas a user with permission
    public function test_get_file_types_with_permission()
    {
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->get(route('admin.getCustFileTypes'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            ['value', 'text']
        ]);
    }

    //  Test get file typesas a user with permission
    public function test_get_file_types_as_installer()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.getCustFileTypes'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            ['value', 'text']
        ]);
    }





    // //  Test submit the change cust id form as a guest
    // public function test_file_typesd_form_as_guest()
    // {
    //     $data = [
    //         'original_id' => factory(Customers::class)->create()->cust_id,
    //         'cust_id' => 53243245342
    //     ];
    //     $response = $this->post(route('admin.submitCustomerID'), $data);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // //  Test submit the change cust id form as a user without permissions
    // public function test_file_typesd_form_no_permission()
    // {
    //     $data = [
    //         'original_id' => factory(Customers::class)->create()->cust_id,
    //         'cust_id' => 53243245342
    //     ];
    //     $response = $this->actingAs($this->getTech())->post(route('admin.submitCustomerID'), $data);

    //     $response->assertStatus(403);
    // }

    // //  Test submit the change cust id for as a user with permission
    // public function test_file_typesd_form_with_permission()
    // {
    //     $customer = factory(Customers::class)->create();
    //     $data = [
    //         'original_id' => $customer->cust_id,
    //         'cust_id'     => 23324
    //     ];
    //     $user = $this->userWithPermission('Manage Customers');
    //     $response = $this->actingAs($user)->post(route('admin.submitCustomerID'), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }

    // //  Test submit the change cust id for as a user with permission
    // public function test_file_typesd_form_as_installer()
    // {
    //     $customer = factory(Customers::class)->create();
    //     $data = [
    //         'original_id' => $customer->cust_id,
    //         'cust_id'     => 3423
    //     ];
    //     $user = $this->getInstaller();
    //     $response = $this->actingAs($user)->post(route('admin.submitCustomerID'), $data);

    //     $response->assertSuccessful();
    //     $response->assertJson(['success' => true]);
    // }






}
