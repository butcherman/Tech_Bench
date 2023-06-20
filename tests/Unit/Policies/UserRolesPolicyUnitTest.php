<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\UserRoles;
use App\Policies\UserRolesPolicy;
use Tests\TestCase;

/**
 * Tests are run using default role parameters
 */
class UserRolesPolicyUnitTest extends TestCase
{
    protected $role;
    protected $tech;
    protected $reports;
    protected $admin;
    protected $installer;
    protected $policyObj;

    public function setUp():void
    {
        parent::setUp();

        $this->role = UserRoles::factory()->create(['allow_edit' => true]);
        $this->tech = User::factory()->create(['role_id' => 4]);
        $this->reports = User::factory()->create(['role_id' => 3]);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->installer = User::factory()->create(['role_id' => 1]);
        $this->policyObj = new UserRolesPolicy;
    }

    public function test_view_any()
    {
        $this->assertFalse($this->policyObj->viewAny($this->tech));
        $this->assertFalse($this->policyObj->viewAny($this->reports));
        $this->assertTrue($this->policyObj->viewAny($this->admin));
        $this->assertTrue($this->policyObj->viewAny($this->installer));
    }

    public function test_view()
    {
        $this->assertFalse($this->policyObj->view($this->tech));
        $this->assertFalse($this->policyObj->view($this->reports));
        $this->assertTrue($this->policyObj->view($this->admin));
        $this->assertTrue($this->policyObj->view($this->installer));
    }

    public function test_create()
    {
        $this->assertFalse($this->policyObj->create($this->tech));
        $this->assertFalse($this->policyObj->create($this->reports));
        $this->assertTrue($this->policyObj->create($this->admin));
        $this->assertTrue($this->policyObj->create($this->installer));
    }

    public function test_update()
    {
        $this->assertFalse($this->policyObj->update($this->tech, $this->role));
        $this->assertFalse($this->policyObj->update($this->reports, $this->role));
        $this->assertTrue($this->policyObj->update($this->admin, $this->role));
        $this->assertTrue($this->policyObj->update($this->installer, $this->role));
        $this->assertFalse($this->policyObj->update($this->installer, UserRoles::find(1)));
    }

    public function test_delete()
    {
        $this->assertFalse($this->policyObj->delete($this->tech, $this->role));
        $this->assertFalse($this->policyObj->delete($this->reports, $this->role));
        $this->assertTrue($this->policyObj->delete($this->admin, $this->role));
        $this->assertTrue($this->policyObj->delete($this->installer, $this->role));
        $this->assertFalse($this->policyObj->delete($this->installer, UserRoles::find(1)));
    }
}
