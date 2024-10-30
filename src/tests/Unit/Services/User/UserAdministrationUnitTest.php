<?php

namespace Tests\Unit\Services\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserAdministrationUnitTest extends TestCase
{
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

    public function test_destroy_user(): void
    {
        $user = User::factory()->create();

        $testObj = new UserAdministrationService;
        $testObj->destroyUser($user);

        $this->assertSoftDeleted('users', $user->only(['user_id', 'username']));
    }

    public function test_destroy_user_force(): void
    {
        $user = User::factory()->create();

        $testObj = new UserAdministrationService;
        $testObj->destroyUser($user, true);

        $this->assertDatabaseMissing('users', $user->only(['user_id', 'username']));
    }

    // TODO - Test Try Catch - User has stuff....

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
}
