<?php

namespace Tests\Unit\User;

use Tests\TestCase;

use App\Domains\Users\GetUserRoles;

class GetUserRolesTest extends TestCase
{
    protected $roleObj;
    protected $defaultData = [
        ['value' => 1, 'text' => 'Installer'],
        ['value' => 2, 'text' => 'Administrator'],
        ['value' => 3, 'text' => 'Reports'],
        ['value' => 4, 'text' => 'Tech'],
    ];

    public function setUp():void
    {
        Parent::setup();

        $this->roleObj     = new GetUserRoles;
        $this->defaultData = collect($this->defaultData);
    }

    //  First test will pull all default roles and verify they are all returned
    public function test_get_role_list_return_all()
    {
        $roleList = $this->roleObj->getRoleList(false)->toJson();
        $this->assertEquals($this->defaultData->toJson(), $roleList);
    }

    //  Test will pull all default roles and verify they are all returned when an Installer level pulls data
    public function test_get_role_list_return_all_installer()
    {
        $roleList = $this->actingAs($this->getInstaller())->roleObj->getRoleList(true)->toJson();
        $this->assertEquals($this->defaultData->toJson(), $roleList);
    }

    //  Test will pull all default roles and verify they are all returned when an Administrator level pulls data
    public function test_get_role_list_return_all_no_installer()
    {
        $roleList = $this->actingAs($this->getAdministrator())->roleObj->getRoleList(true)->toJson();
        $this->assertEquals($this->defaultData->forget(0)->toJson(), $roleList);
    }

    //  Test will pull all default roles and verify they are all returned when a manager level pulls data
    public function test_get_role_list_return_all_no_installer_or_admin()
    {
        $roleList = $this->actingAs($this->getTech())->roleObj->getRoleList(true)->toJson();
        $this->assertEquals($this->defaultData->forget([0, 1])->toJson(), $roleList);
    }
}
