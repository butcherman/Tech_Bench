<?php

namespace App\Service\Admin;

use App\Events\Feature\FeatureChangedEvent;
use App\Http\Requests\Admin\User\UserRoleRequest;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use App\Service\Cache;
use App\Service\CheckDatabaseError;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserRoleAdministrationService
{
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
    public function createNewRole(UserRoleRequest $requestData): UserRole
    {
        $newRole = UserRole::create($requestData->only(['name', 'description']));

        $this->processPermissions($requestData->permissions, $newRole);
        $this->flushRoleCache();

        event(new FeatureChangedEvent);

        Log::info(
            'New User Role created by '.$requestData->user()->username,
            $newRole->toArray()
        );

        return $newRole;
    }

    /**
     * Update an existing Role
     */
    public function updateExistingRole(UserRoleRequest $requestData, UserRole $role): UserRole
    {
        $role->update($requestData->only(['name', 'description']));

        $this->processPermissions($requestData->permissions, $role);
        $this->flushRoleCache();

        event(new FeatureChangedEvent);

        Log::info(
            'User Role Updated by '.$requestData->user()->username,
            $role->toArray()
        );

        return $role;
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

            Log::stack(['daily', 'auth'])
                ->notice('Role '.$role->name.' has been deleted by '.
                    request()->user()->username);

        } catch (QueryException $e) {
            // If the Role is in use, throw in use exception
            CheckDatabaseError::check($e, __('admin.user-role.in-use'));
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
        Cache::clearCache('user_roles');
    }
}
