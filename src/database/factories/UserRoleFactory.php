<?php

namespace Database\Factories;

use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(2),
            'description' => 'New test role',
        ];
    }

    /**
     * Create permissions attached to this role
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($role) {
            $permissions = UserRolePermissionType::all();
            foreach ($permissions as $perm) {
                UserRolePermission::create([
                    'role_id' => $role->role_id,
                    'perm_type_id' => $perm->perm_type_id,
                    'allow' => $this->faker->boolean(),
                ]);
            }
        });
    }
}
