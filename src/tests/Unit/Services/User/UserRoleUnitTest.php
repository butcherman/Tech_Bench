<?php

namespace Tests\Unit\Services\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserRolePermissionType;
use App\Services\User\UserRoleService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserRoleUnitTest extends TestCase
{
    use WithFaker;

    /*
    |---------------------------------------------------------------------------
    | getAllRoles()
    |---------------------------------------------------------------------------
    */
    public function test_get_all_roles(): void
    {
        $shouldBe = UserRole::get()->toArray();

        $testObj = new UserRoleService;
        $res = $testObj->getAllRoles();

        $this->assertEquals($shouldBe, $res->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | getRole()
    |---------------------------------------------------------------------------
    */
    public function test_get_role(): void
    {
        $shouldBe = UserRole::find(1)->toArray();

        $testObj = new UserRoleService;
        $res = $testObj->getRole(1);

        $this->assertEquals($shouldBe, $res->toArray());
    }

    public function test_get_role_permission_types(): void
    {
        $shouldBe = UserRolePermissionType::all()->groupBy('group');

        $testObj = new UserRoleService;
        $res = $testObj->getRolePermissionTypes();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | createNewRole()
    |---------------------------------------------------------------------------
    */
    public function test_create_new_role(): void
    {
        Event::fake();

        $data = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $testObj = new UserRoleService;
        $res = $testObj->createNewRole(collect($data));

        $this->assertEquals($data['name'], $res->name);
        $this->assertEquals($data['description'], $res->description);

        $this->assertDatabaseHas('user_roles', [
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        $this->assertDatabaseHas('user_role_permissions', [
            'perm_type_id' => 1,
            'allow' => $data['permissions']['1'],
        ]);
        $this->assertDatabaseHas('user_role_permissions', [
            'perm_type_id' => 5,
            'allow' => $data['permissions']['5'],
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateExistingRole()
    |---------------------------------------------------------------------------
    */
    public function test_update_existing_role(): void
    {
        Event::fake();

        $testRole = UserRole::factory()->createQuietly();
        $data = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $testObj = new UserRoleService;
        $res = $testObj->updateExistingRole(collect($data), $testRole);

        $this->assertEquals($data['name'], $res->name);
        $this->assertEquals($data['description'], $res->description);

        $this->assertDatabaseHas('user_roles', [
            'role_id' => $testRole->role_id,
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        $this->assertDatabaseHas('user_role_permissions', [
            'perm_type_id' => 1,
            'allow' => $data['permissions']['1'],
        ]);
        $this->assertDatabaseHas('user_role_permissions', [
            'perm_type_id' => 5,
            'allow' => $data['permissions']['5'],
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyRole()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_role(): void
    {
        $testRole = UserRole::factory()->createQuietly();

        $testObj = new UserRoleService;
        $testObj->destroyRole($testRole);

        $this->assertDatabaseMissing('user_roles', $testRole->toArray());
    }

    public function test_destroy_role_in_use(): void
    {
        $testRole = UserRole::factory()->createQuietly();
        User::factory()->createQuietly(['role_id' => $testRole->role_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new UserRoleService;
        $testObj->destroyRole($testRole);

        $this->assertDatabaseHas('user_roles', $testRole->toArray());
    }
}
