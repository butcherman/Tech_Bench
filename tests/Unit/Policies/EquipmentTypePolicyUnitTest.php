<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\EquipmentTypePolicy;
use Tests\TestCase;

class EquipmentTypePolicyUnitTest extends TestCase
{
    protected $user;

    protected $tech;

    protected $reports;

    protected $admin;

    protected $installer;

    protected $policyObj;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->tech = User::factory()->create(['role_id' => 4]);
        $this->reports = User::factory()->create(['role_id' => 3]);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->installer = User::factory()->create(['role_id' => 1]);
        $this->policyObj = new EquipmentTypePolicy;
    }

    public function test_viewAny()
    {
        $this->assertFalse($this->policyObj->viewAny($this->tech));
        $this->assertFalse($this->policyObj->viewAny($this->reports));
        $this->assertTrue($this->policyObj->viewAny($this->admin));
        $this->assertTrue($this->policyObj->viewAny($this->installer));
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
        $this->assertFalse($this->policyObj->update($this->tech));
        $this->assertFalse($this->policyObj->update($this->reports));
        $this->assertTrue($this->policyObj->update($this->admin));
        $this->assertTrue($this->policyObj->update($this->installer));
    }

    public function test_delete()
    {
        $this->assertFalse($this->policyObj->delete($this->tech));
        $this->assertFalse($this->policyObj->delete($this->reports));
        $this->assertTrue($this->policyObj->delete($this->admin));
        $this->assertTrue($this->policyObj->delete($this->installer));
    }
}
