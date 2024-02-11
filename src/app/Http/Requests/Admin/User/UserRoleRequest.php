<?php

namespace App\Http\Requests\Admin\User;

use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Service\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user_role) {
            return $this->user()->can('update', $this->user_role);
        }

        return $this->user()->can('create', UserRole::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->getMethod('delete')) {
            return [];
        }

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('user_roles')->ignore($this->user_role),
            ],
            'description' => 'required|string',
            'permissions' => 'required|array',
        ];
    }

    /**
     * Build a new Role and assign permissions
     */
    public function processNewRole()
    {
        $newRole = UserRole::create($this->only(['name', 'description']));

        $this->processPermissions($newRole->role_id);
        $this->flushRoleCache();

        return $newRole;
    }

    /**
     * Update an existing Role
     */
    public function processExistingRole()
    {
        $this->user_role->update($this->only(['name', 'description']));

        $this->processPermissions($this->user_role->role_id);
        $this->flushRoleCache();

        return $this->user_role;
    }

    /**
     * Delete an existing Role
     */
    public function destroyRole()
    {
        try {
            $this->user_role->delete();
            $this->flushRoleCache();
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(__('admin.user-role.in-use'), 0, $e);
            } else {
                throw new GeneralQueryException('', 0, $e);
            }
        }
    }

    /**
     * Go through all Role Permissions and build/update in Database
     */
    protected function processPermissions($roleId)
    {
        foreach ($this->permissions as $key => $value) {
            if ($value !== null) {
                UserRolePermission::firstOrCreate([
                    'role_id' => $roleId,
                    'perm_type_id' => $key,
                ])->update(['allow' => $value]);
            }
        }
    }

    /**
     * Flush the Roles Cache so it can be rebuilt
     */
    protected function flushRoleCache()
    {
        Cache::clearCache('user_roles');
    }
}
