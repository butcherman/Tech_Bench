<?php

namespace Database\Factories;

use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Models\UserRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRolesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = UserRoles::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(2),
            'description' => 'New test role',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($role) {
            $permissions = UserRolePermissionTypes::all();
            foreach ($permissions as $perm) {
                UserRolePermissions::create([
                    'role_id' => $role->role_id,
                    'perm_type_id' => $perm->perm_type_id,
                    'allow' => $this->faker->boolean(),
                ]);
            }
        });
    }
}
