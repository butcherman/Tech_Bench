<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserRoles;
use App\Notifications\User\SendAuthCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
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

    public function test_generate_verification_code()
    {
        Notification::fake();

        $user = User::factory()->create(['phone' => 8165551212, 'receive_sms' => true]);
        $user->generateVerificationCode(true);

        $this->assertDatabaseHas('user_codes', ['user_id' => $user->user_id]);

        Notification::assertSentTo($user, SendAuthCode::class);
    }

    public function test_generate_remember_device_token()
    {
        $user = User::factory()->create();

        $token = $user->generateRememberDeviceToken();

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->user_id,
            'token' => $token,
        ]);
    }

    public function test_validate_device_token_good()
    {
        $user = User::factory()->create();
        $token = $user->generateRememberDeviceToken();

        $this->assertTrue($user->validateDeviceToken($token));
    }

    public function test_validate_device_token_bad()
    {
        $user = User::factory()->create();
        $token = $user->generateRememberDeviceToken();

        $this->assertFalse($user->validateDeviceToken(Str::random(60)));
    }
}
