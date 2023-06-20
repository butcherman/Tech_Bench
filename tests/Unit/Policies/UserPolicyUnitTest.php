<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Tests\TestCase;

/**
 * Tests are run using default role parameters
 */
class UserPolicyUnitTest extends TestCase
{
    protected $user;
    protected $tech;
    protected $reports;
    protected $admin;
    protected $installer;
    protected $policyObj;

    public function setUp():void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->tech = User::factory()->create(['role_id' => 4]);
        $this->reports = User::factory()->create(['role_id' => 3]);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->installer = User::factory()->create(['role_id' => 1]);
        $this->policyObj = new UserPolicy;
    }

    public function test_manage()
    {
        $this->assertFalse($this->policyObj->manage($this->tech));
        $this->assertFalse($this->policyObj->manage($this->reports));
        $this->assertTrue($this->policyObj->manage($this->admin));
        $this->assertTrue($this->policyObj->manage($this->installer));
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
        $this->assertTrue($this->policyObj->update($this->user, $this->user));
        $this->assertFalse($this->policyObj->update($this->tech, $this->user));
        $this->assertFalse($this->policyObj->update($this->reports, $this->user));
        $this->assertTrue($this->policyObj->update($this->admin, $this->user));
        $this->assertTrue($this->policyObj->update($this->installer, $this->user));
    }

    public function test_destroy()
    {
        $this->assertFalse($this->policyObj->destroy($this->tech, $this->user));
        $this->assertFalse($this->policyObj->destroy($this->reports, $this->user));
        $this->assertTrue($this->policyObj->destroy($this->admin, $this->user));
        $this->assertTrue($this->policyObj->destroy($this->installer, $this->user));
    }
}
