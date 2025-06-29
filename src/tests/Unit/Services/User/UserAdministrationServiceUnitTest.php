<?php

namespace Tests\Unit\Services\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\CustomerNote;
use App\Models\DeviceToken;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;

class UserAdministrationServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getAllUsers()
    |---------------------------------------------------------------------------
    */
    public function test_get_all_users(): void
    {
        $this->seed(UserSeeder::class);

        $testObj = new UserAdministrationService;
        $response = $testObj->getAllUsers();

        $this->assertEquals(22, $response->count());
    }

    public function test_get_trashed(): void
    {
        $this->seed(UserSeeder::class);

        $testObj = new UserAdministrationService;
        $response = $testObj->getAllUsers(true);

        $this->assertEquals(5, $response->count());
    }

    /*
    |---------------------------------------------------------------------------
    | getInstallerUsers()
    |---------------------------------------------------------------------------
    */
    public function test_get_installer_users(): void
    {
        $testObj = new UserAdministrationService;
        $response = $testObj->getInstallerUsers();

        $this->assertEquals(
            $response->toArray(),
            User::where('role_id', 1)
                ->get(['user_id', 'first_name', 'last_name'])
                ->makeHidden(['initials', 'role_name', 'full_name'])
                ->makeVisible('user_id')
                ->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | getAdminUsers()
    |---------------------------------------------------------------------------
    */
    public function test_get_admin_users(): void
    {
        $testObj = new UserAdministrationService;
        $response = $testObj->getAdminUsers();

        $this->assertEquals(
            $response->toArray(),
            User::whereIn('role_id', [1, 2])
                ->get(['user_id', 'first_name', 'last_name'])
                ->makeHidden(['initials', 'role_name', 'full_name'])
                ->makeVisible('user_id')
                ->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | createUser()
    |---------------------------------------------------------------------------
    */
    public function test_create_user(): void
    {
        Bus::fake();

        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $testObj = new UserAdministrationService;
        $newUser = $testObj->createUser(collect($data));

        $this->assertDatabaseHas('users', $data);
        $this->assertEquals($newUser->only([
            'username',
            'first_name',
            'last_name',
            'email',
            'role_id',
        ]), $data);

        Bus::assertDispatched(SendWelcomeEmailJob::class);
        Bus::assertDispatched(CreateUserSettingsJob::class);
    }

    public function test_create_user_no_email(): void
    {
        Bus::fake();

        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $testObj = new UserAdministrationService;
        $newUser = $testObj->createUser(collect($data), true);

        $this->assertDatabaseHas('users', $data);
        $this->assertEquals($newUser->only([
            'username',
            'first_name',
            'last_name',
            'email',
            'role_id',
        ]), $data);

        Bus::assertNotDispatched(SendWelcomeEmailJob::class);
        Bus::assertDispatched(CreateUserSettingsJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateUser()
    |---------------------------------------------------------------------------
    */
    public function test_update_user(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $testObj = new UserAdministrationService;
        $updated = $testObj->updateUser(collect($data), $user);

        $this->assertDatabaseHas('users', $data);
        $this->assertEquals($updated->only([
            'username',
            'first_name',
            'last_name',
            'email',
            'role_id',
        ]), $data);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyUser()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_user(): void
    {
        $user = User::factory()->create();

        $testObj = new UserAdministrationService;
        $testObj->destroyUser($user);

        $this->assertSoftDeleted(
            'users',
            $user->only(['user_id', 'username'])
        );
    }

    public function test_destroy_user_force(): void
    {
        $user = User::factory()->create();

        $testObj = new UserAdministrationService;
        $testObj->destroyUser($user, true);

        $this->assertDatabaseMissing(
            'users',
            $user->only(['user_id', 'username'])
        );
    }

    public function test_destroy_user_has_contributions(): void
    {
        $user = User::factory()->create();

        CustomerNote::factory()
            ->count(5)
            ->create(['created_by' => $user->user_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new UserAdministrationService;
        $testObj->destroyUser($user, true);

        $this->assertDatabaseHas('users', $user->only(['user_id', 'username']));
    }

    /*
    |---------------------------------------------------------------------------
    | restoreUser()
    |---------------------------------------------------------------------------
    */
    public function test_restore(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $testObj = new UserAdministrationService;
        $testObj->restoreUser($user);

        $this->assertDatabaseHas(
            'users',
            array_merge(
                $user->only(['user_id', 'username']),
                ['deleted_at' => null],
            )
        );
    }

    /*
    |---------------------------------------------------------------------------
    | clearTwoFaSettings()
    |---------------------------------------------------------------------------
    */
    public function test_clear_two_fa_settings(): void
    {
        $user = User::factory()->create();
        $user->two_factor_secret = Str::uuid();
        $user->two_factor_recovery_codes = Str::uuid();
        $user->two_factor_confirmed_at = now();
        $user->two_factor_via = 'authenticator';

        DeviceToken::factory()->count(5)->create(['user_id' => $user->user_id]);

        $testObj = new UserAdministrationService;
        $testObj->clearTwoFaSettings($user);

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_via' => null,
        ]);

        $this->assertDatabaseMissing('device_tokens', [
            'user_id' => $user->user_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | resetPasswordExpire()
    |---------------------------------------------------------------------------
    */
    public function test_reset_password_expire(): void
    {
        config(['auth.passwords.settings.expire' => 60]);

        $user = User::factory()
            ->create(['password_expires' => now()->addDays(90)]);

        $testObj = new UserAdministrationService;
        $testObj->resetPasswordExpire($user);

        $this->assertEquals(
            $user->fresh()->password_expires->format('m-d-y'),
            now()->addDays(60)->format('m-d-y')
        );
    }

    public function test_reset_password_expire_was_null(): void
    {
        config(['auth.passwords.settings.expire' => 60]);

        $user = User::factory()->create();

        $testObj = new UserAdministrationService;
        $testObj->resetPasswordExpire($user);

        $this->assertEquals(
            $user->fresh()->password_expires->format('m-d-y'),
            now()->addDays(60)->format('m-d-y')
        );
    }

    public function test_reset_password_expire_expires_soon(): void
    {
        config(['auth.passwords.settings.expire' => 30]);

        $user = User::factory()
            ->create(['password_expires' => now()->addDays(10)]);

        $testObj = new UserAdministrationService;
        $testObj->resetPasswordExpire($user);

        $this->assertEquals(
            $user->fresh()->password_expires->format('m-d-y'),
            now()->addDays(10)->format('m-d-y')
        );
    }

    public function test_reset_password_expire_expired_already(): void
    {
        config(['auth.passwords.settings.expire' => 30]);

        $user = User::factory()
            ->create(['password_expires' => now()->subDays(5)]);

        $testObj = new UserAdministrationService;
        $testObj->resetPasswordExpire($user);

        $this->assertEquals(
            $user->fresh()->password_expires->format('m-d-y'),
            now()->subDays(5)->format('m-d-y')
        );
    }

    public function test_reset_password_expire_set_to_zero(): void
    {
        config(['auth.passwords.settings.expire' => 0]);

        $user = User::factory()
            ->create(['password_expires' => now()->addDays(30)]);

        $testObj = new UserAdministrationService;
        $testObj->resetPasswordExpire($user);

        $this->assertEquals(
            $user->fresh()->password_expires,
            null
        );
    }

    public function test_reset_password_expire_set_to_zero_expired_already(): void
    {
        config(['auth.passwords.settings.expire' => 0]);

        $user = User::factory()
            ->create(['password_expires' => now()->subDays(5)]);

        $testObj = new UserAdministrationService;
        $testObj->resetPasswordExpire($user);

        $this->assertEquals(
            $user->fresh()->password_expires->format('m-d-y'),
            now()->subDays(5)->format('m-d-y')
        );
    }

    /*
    |---------------------------------------------------------------------------
    | resetAllPasswordExpire()
    |---------------------------------------------------------------------------
    */
    public function test_reset_all_password_expire(): void
    {
        User::factory()->count(5)->create();

        /** @var UserAdministrationService */
        $stub = $this->partialMock(UserAdministrationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('resetPasswordExpire')->times(6);
        });

        $stub->resetAllPasswordExpire();
    }
}
