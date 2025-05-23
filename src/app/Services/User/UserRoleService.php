<?php

namespace App\Services\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Facades\CacheData;
use App\Facades\DbException;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class UserRoleService
{
    /**
     * Get a list of all assigned roles
     */
    public function getAllRoles(): Collection
    {
        return CacheData::userRoles();
    }

    /**
     * Return data for one specific role
     */
    public function getRole(int $roleId): UserRole
    {
        return UserRole::find($roleId);
    }

    /**
     * Return all Role Permissions Grouped by Category.
     */
    public function getRolePermissionTypes(): Collection
    {
        return UserRolePermissionType::all()
            ->filter(function ($perm) {
                return $perm->feature_enabled;
            })->groupBy('group');
    }

    /**
     * Build a new Role and assign permissions
     */
    public function createNewRole(SupportCollection $requestData): UserRole
    {
        $newRole = UserRole::create(
            $requestData->only(['name', 'description'])->toArray()
        );

        $this->processPermissions($requestData->get('permissions'), $newRole);
        $this->flushRoleCache();

        event(new FeatureChangedEvent);

        return $newRole;
    }

    /**
     * Update an existing Role
     */
    public function updateExistingRole(
        SupportCollection $requestData,
        UserRole $role
    ): UserRole {
        $role->update($requestData->only(['name', 'description'])->toArray());

        $this->processPermissions($requestData->get('permissions'), $role);
        $this->flushRoleCache();

        event(new FeatureChangedEvent);

        return $role->fresh();
    }

    /**
     * Delete an existing Role
     *
     * Note: A role can only be deleted if it is not currently in use
     */
    public function destroyRole(UserRole $role): void
    {
        try {
            $role->delete();
            $this->flushRoleCache();
        } catch (QueryException $e) {
            // If the Role is in use, throw in use exception
            DbException::check($e, __('admin.user-role.in-use'));
        }
    }

    /**
     * Go through all Role Permissions and build/update in Database
     */
    protected function processPermissions(array $permissions, UserRole $role): void
    {
        Log::debug('Updating User Role Permissions', [
            'role_id' => $role->role_id,
            'role_name' => $role->name,
            'permissions' => $permissions,
        ]);

        foreach ($permissions as $key => $value) {
            if (! is_null($value)) {
                UserRolePermission::firstOrCreate([
                    'role_id' => $role->role_id,
                    'perm_type_id' => $key,
                ])->update(['allow' => $value]);
            }
        }
    }

    /**
     * Flush the Roles Cache so it can be rebuilt
     */
    protected function flushRoleCache(): void
    {
        CacheData::clearCache('userRoles');
    }
}
