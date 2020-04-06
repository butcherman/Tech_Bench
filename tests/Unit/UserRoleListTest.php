<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\User;
use App\UserRoleType;

class UserRoleListTest extends TestCase
{
    public $user;

    public function setUp(): void
    {
        Parent::setup();

        $this->user = $this->getTech();
    }

    //  Verify that with installer perimssions, all user roles will be pulled
    public function test_user_permission_list_with_installer()
    {
        $user = $this->getInstaller();
        $response  = $this->actingAs($user)->get(route('admin.user.create'));
        $data = $response->getOriginalContent()->getData()['roles'];

        $roleTypes = UserRoleType::select('role_id as value', 'name as text')->get()->toArray();

        $this->assertTrue($roleTypes == $data);
    }

    //  Verify that with admin perimssions, the installer role is missing
    public function test_user_permission_list_as_admin()
    {
        $user = factory(User::class)->create([
            'role_id' => 2
        ]);

        $response  = $this->actingAs($user)->get(route('admin.user.create'));

        $roleTypes = UserRoleType::select('role_id as value', 'name as text')->get()->toArray();
        unset($roleTypes[0]);
        $roleTypes = array_values($roleTypes);

        $data = $response->getOriginalContent()->getData()['roles'];

        $this->assertTrue($roleTypes == $data);
    }

    //  Verify that with normal user perimssions, the installer and admin roles are missing
    public function test_user_permission_list_as_user_manager()
    {
        $user = $this->userWithPermission('Manage Users');

        $response  = $this->actingAs($user)->get(route('admin.user.create'));

        $roleTypes = UserRoleType::select('role_id as value', 'name as text')->get()->toArray();
        unset($roleTypes[0]);
        unset($roleTypes[1]);
        $roleTypes = array_values($roleTypes);

        $data = $response->getOriginalContent()->getData()['roles'];

        $this->assertTrue($roleTypes == $data);
    }






    //  Verify that with installer perimssions, all user roles will be pulled
    public function test_user_permission_list_with_installer_edit_page()
    {
        $user = $this->getInstaller();
        $response  = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));
        $data = $response->getOriginalContent()->getData()['roles'];

        $roleTypes = UserRoleType::select('role_id as value', 'name as text')->get()->toArray();

        $this->assertTrue($roleTypes == $data);
    }

    //  Verify that with admin perimssions, the installer role is missing
    public function test_user_permission_list_as_admin_edit_page()
    {
        $user = factory(User::class)->create([
            'role_id' => 2
        ]);

        $response  = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));

        $roleTypes = UserRoleType::select('role_id as value', 'name as text')->get()->toArray();
        unset($roleTypes[0]);
        $roleTypes = array_values($roleTypes);

        $data = $response->getOriginalContent()->getData()['roles'];

        $this->assertTrue($roleTypes == $data);
    }

    //  Verify that with normal user perimssions, the installer and admin roles are missing
    public function test_user_permission_list_as_user_manager_edit_page()
    {
        $user = $this->userWithPermission('Manage Users');

        $response  = $this->actingAs($user)->get(route('admin.user.edit', $this->user->user_id));

        $roleTypes = UserRoleType::select('role_id as value', 'name as text')->get()->toArray();
        unset($roleTypes[0]);
        unset($roleTypes[1]);
        $roleTypes = array_values($roleTypes);

        // dd($response);

        $data = $response->getOriginalContent()->getData()['roles'];

        $this->assertTrue($roleTypes == $data);
    }









    //  Test invalid call to inactive users route
    public function test_inactive_user_invalid_route()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('admin.user.show', 'random'));

        $response->assertStatus(404);
    }
}
