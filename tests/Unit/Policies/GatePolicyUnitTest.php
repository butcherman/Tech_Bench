<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\GatePolicy;
use Tests\TestCase;

class GatePolicyUnitTest extends TestCase
{
    protected $tech;

    protected $reports;

    protected $admin;

    protected $installer;

    public function setUp(): void
    {
        parent::setUp();

        $this->tech = User::factory()->create(['role_id' => 4]);
        $this->reports = User::factory()->create(['role_id' => 3]);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->installer = User::factory()->create(['role_id' => 1]);
    }

    /**
     * Test Admin Link Gate using default parameters
     */
    public function test_admin_link_gate()
    {
        $obj = new GatePolicy;

        $this->assertFalse($obj->adminLink($this->tech));
        $this->assertFalse($obj->adminLink($this->reports));
        $this->assertTrue($obj->adminLink($this->admin));
        $this->assertTrue($obj->adminLink($this->installer));
    }

    /**
     * Test Reports Link Gate using default parameters
     */
    public function test_reports_link_gate()
    {
        $obj = new GatePolicy;

        $this->assertFalse($obj->reportsLink($this->tech));
        $this->assertTrue($obj->reportsLink($this->reports));
        $this->assertTrue($obj->reportsLink($this->admin));
        $this->assertTrue($obj->reportsLink($this->installer));
    }
}
