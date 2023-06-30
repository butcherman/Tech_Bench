<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\AppSettingsPolicy;
use Tests\TestCase;

class AppSettingsPolicyUnitTest extends TestCase
{
    protected $tech;

    protected $reports;

    protected $admin;

    protected $installer;

    protected $policyObj;

    public function setUp(): void
    {
        parent::setUp();

        $this->tech = User::factory()->create(['role_id' => 4]);
        $this->reports = User::factory()->create(['role_id' => 3]);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->installer = User::factory()->create(['role_id' => 1]);
        $this->policyObj = new AppSettingsPolicy;
    }

    public function test_view_any()
    {
        $this->assertFalse($this->policyObj->viewAny($this->tech));
        $this->assertFalse($this->policyObj->viewAny($this->reports));
        $this->assertFalse($this->policyObj->viewAny($this->admin));
        $this->assertTrue($this->policyObj->viewAny($this->installer));
    }
}
