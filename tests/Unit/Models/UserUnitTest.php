<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserRoles;
use Carbon\Carbon;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Test Route Model Binding
     */
    public function test_route_model_binding()
    {
        $this->assertEquals($this->user->getRouteKeyName(), 'username');
    }

    /**
     * Test Additional Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('full_name', $this->user->toArray());
        $this->assertArrayHasKey('initials', $this->user->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $role = UserRoles::where('role_id', $this->user->role_id)->first();
        $this->assertEquals($this->user->UserRole->role_id, $role->role_id);
    }

    /**
     * Test New Expire Time Methods
     */
    public function test_new_expire_time()
    {
        $this->assertEquals(date_format($this->user->getNewExpireTime(), 'Y/m/d'), date_format(Carbon::now()->addDays(config('auth.passwords.settings.expire')), 'Y/m/d'));
    }

    public function test_new_expire_time_no_expire()
    {
        $this->assertEquals(date_format($this->user->getNewExpireTime(true), 'Y/m/d'), date_format(Carbon::yesterday(), 'Y/m/d'));
    }
}
