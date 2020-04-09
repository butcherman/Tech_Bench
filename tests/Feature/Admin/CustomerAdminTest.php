<?php

namespace Tests\Feature\admin;

use App\Customers;
use Tests\TestCase;
use App\CustomerFiles;
use App\CustomerFileTypes;

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

    //  Test get file types as a user with permission
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

    //  Test submit the new file type form as a guest
    public function test_submit_new_file_type_as_guest()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $data = [
            'name' => $name->description,
        ];
        $response = $this->post(route('admin.submitNewFileType'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test submit the new file type form as a user without permissions
    public function test_submit_new_file_type_no_permission()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $data = [
            'name' => $name->description,
        ];
        $response = $this->actingAs($this->getTech())->post(route('admin.submitNewFileType'), $data);

        $response->assertStatus(403);
    }

    //  Test submit the new file type for as a user with permission
    public function test_submit_new_file_type_with_permission()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $data = [
            'name' => $name->description,
        ];
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->post(route('admin.submitNewFileType'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test submit the new file type for as a user with permission
    public function test_submit_new_file_type_as_installer()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $data = [
            'name' => $name->description,
        ];
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->post(route('admin.submitNewFileType'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test submit the change file type name form as a user with permission but validation error
    public function test_submit_new_file_type_as_installer_validateion_error()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $data = [
            'name' => null,
        ];
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->post(route('admin.submitNewFileType'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    //  Test submit the change file type name form as a guest
    public function test_submit_edit_file_type_as_guest()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $existing = factory(CustomerFileTypes::class)->create();
        $data = [
            'name' => $name->description,
            'id'   => $existing->file_type_id,
        ];
        $response = $this->put(route('admin.setCustFileTypes'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test submit the change file type name form as a user without permissions
    public function test_submit_edit_file_type_no_permission()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $existing = factory(CustomerFileTypes::class)->create();
        $data = [
            'name' => $name->description,
            'id'   => $existing->file_type_id,
        ];
        $response = $this->actingAs($this->getTech())->put(route('admin.setCustFileTypes'), $data);

        $response->assertStatus(403);
    }

    //  Test submit the change file type name form as a user with permission
    public function test_submit_edit_file_type_with_permission()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $existing = factory(CustomerFileTypes::class)->create();
        $data = [
            'name' => $name->description,
            'id'   => $existing->file_type_id,
        ];
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->put(route('admin.setCustFileTypes'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test submit the change file type name form as a user with permission
    public function test_submit_edit_file_type_as_installer()
    {
        $name = factory(CustomerFileTypes::class)->make();
        $existing = factory(CustomerFileTypes::class)->create();
        $data = [
            'name' => $name->description,
            'id'   => $existing->file_type_id,
        ];
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->put(route('admin.setCustFileTypes'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test submit the change file type name form as a user with permission but validation error
    public function test_submit_edit_file_type_as_installer_validateion_error()
    {
        $name = factory(CustomerFileTypes::class)->make();
        // $existing = factory(CustomerFileTypes::class)->create();
        $data = [
            'name' => null,
            'id'   => $name->file_type_id,
        ];
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->put(route('admin.setCustFileTypes'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('id', 'name');
    }

    //  Test delete new file type  as a guest
    public function test_delete_file_type_as_guest()
    {
        $type = factory(CustomerFileTypes::class)->create();

        $response = $this->delete(route('admin.delCustFileType', $type->file_type_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test delete new file type  as a user without permissions
    public function test_delete_file_type_no_permission()
    {
        $type = factory(CustomerFileTypes::class)->create();

        $response = $this->actingAs($this->getTech())->delete(route('admin.delCustFileType', $type->file_type_id));

        $response->assertStatus(403);
    }

    //  Test delete new file type as a user with permission
    public function test_delete_file_type_with_permission()
    {
        $type = factory(CustomerFileTypes::class)->create();

        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->delete(route('admin.delCustFileType', $type->file_type_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
        $this->assertDeleted('customer_file_types', $type->toArray());
    }

    //  Test delete new file type as a user with permission
    public function test_delete_file_type_as_installer()
    {
        $type = factory(CustomerFileTypes::class)->create();

        $user = $this->getInstaller();
        $response = $this->actingAs($user)->delete(route('admin.delCustFileType', $type->file_type_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
        $this->assertDeleted('customer_file_types', $type->toArray());
    }

    //  Test delete new file type when it is in use
    public function test_delete_file_type_that_is_in_use()
    {
        // $customer = factory(Customers::class)->create();
        $type = factory(CustomerFileTypes::class)->create();
        factory(CustomerFiles::class)->create([
            'file_type_id' => $type->file_type_id,
        ]);

        // dd($type->toArray());

        $user = $this->getInstaller();
        $response = $this->actingAs($user)->delete(route('admin.delCustFileType', $type->file_type_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => false, 'reason' => 'In Use']);
        $this->assertDatabaseHas('customer_file_types', $type->toArray());
    }










    //  Test visit the change cust id form as a guest
    public function test_visit_disabled_customers_page_as_guest()
    {
        $response = $this->get(route('admin.disabledCustomers'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit the change cust id form as a user without permissions
    public function test_visit_disabled_customers_page_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.disabledCustomers'));

        $response->assertStatus(403);
    }

    //  Test visit the change cust id for as a user with permission
    public function test_visit_disabled_customers_page_with_permission()
    {
        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->get(route('admin.disabledCustomers'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.customerDisabledList');
    }

    //  Test visit the change cust id for as a user with permission
    public function test_visit_disabled_customers_page_as_installer()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.disabledCustomers'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.customerDisabledList');
    }

    //  Test visit the change cust id form as a guest
    public function test_reenable_customer_as_guest()
    {
        $customer = factory(Customers::class)->create();
        Customers::destroy($customer->cust_id);

        $response = $this->get(route('admin.enableCustomer', $customer->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit the change cust id form as a user without permissions
    public function test_reenable_customer_no_permission()
    {
        $customer = factory(Customers::class)->create();
        Customers::destroy($customer->cust_id);

        $response = $this->actingAs($this->getTech())->get(route('admin.enableCustomer', $customer->cust_id));

        $response->assertStatus(403);
    }

    //  Test visit the change cust id for as a user with permission
    public function test_visit_disabled_users_page_with_permission()
    {
        $customer = factory(Customers::class)->create();
        Customers::destroy($customer->cust_id);

        $user = $this->userWithPermission('Manage Customers');
        $response = $this->actingAs($user)->get(route('admin.enableCustomer', $customer->cust_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test visit the change cust id for as a user with permission
    public function test_reenable_customer_as_installer()
    {
        $customer = factory(Customers::class)->create();
        Customers::destroy($customer->cust_id);

        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.enableCustomer', $customer->cust_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
