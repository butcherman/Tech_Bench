<?php

namespace Tests\Unit\Roles;

use App\Domains\Roles\GetRoles;
use Illuminate\Support\Arr;
use Tests\TestCase;

class GetRolesTest extends TestCase
{
    protected $testObj, $defaultData;

    public function setUp():void
    {
        Parent::setup();

        $this->defaultData = [
            ['role_id' => 1, 'name' => 'Installer',     'description' => 'All Access Administrator', 'allow_edit' => 0],
            ['role_id' => 2, 'name' => 'Administrator', 'description' => 'System Administrator',     'allow_edit' => 0],
            ['role_id' => 3, 'name' => 'Reports',       'description' => 'User who can run reports', 'allow_edit' => 0],
            ['role_id' => 4, 'name' => 'Tech',          'description' => 'Standard User',            'allow_edit' => 0],
        ];
        $this->testObj = new GetRoles;
    }

    public function test_get_role_list()
    {
        $data = $this->actingAs($this->getInstaller())->testObj->getRoleList();

        $this->assertEquals($data->makeHidden(['created_at', 'updated_at', 'UserRolePermissions'])->toArray(), $this->defaultData);
    }

    public function test_get_role_list_limit()
    {
        $data = $this->actingAs($this->getTech())->testObj->getRoleList();

        $newData = $this->defaultData;
        Arr::forget($newData, [0, 1]);

        $this->assertEquals($data->makeHidden(['created_at', 'updated_at', 'UserRolePermissions'])->toArray(), $newData);
    }
}
