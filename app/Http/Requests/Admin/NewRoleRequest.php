<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('hasAccess', 'Manage Permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'allow_edit'            => 'required|boolean',
            'name'                  => 'required',
            'description'           => 'required',
            'role_id'               => 'nullable|exists:user_role_types',
            'user_role_permissions' => 'required|array',
        ];
    }
}
