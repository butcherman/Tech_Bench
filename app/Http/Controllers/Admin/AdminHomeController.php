<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRolePermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AdminHomeController extends Controller
{
    protected $userPermissions;
    /**
     *  Administration Home Page
     */
    public function __invoke()
    {
        Gate::authorize('admin-link', Auth::user());

        $this->userPermissions = UserRolePermissions::where('role_id', Auth::user()->role_id)->with('UserRolePermissionTypes');

        $userBuild = $this->buildUserList();



        return Inertia::render('Admin/index', [
            'links' => array_merge($userBuild),
        ]);
    }

    protected function buildUserList()
    {
        $userBuild = [];
        if($this->getPermissionValue('Manage Users'))
        {
            $userBuild = [
                [
                    'name' => 'Create New User',
                    'icon' => 'fas fa-user-plus',
                    'link' => route('user.create'),
                ],
                [
                    'name' => 'Modify User',
                    'icon' => 'fas fa-user-edit',
                    'link' => '#',
                ],
                [
                    'name' => 'Deactivate User',
                    'icon' => 'fas fa-user-slash',
                    'link' => '#',
                ],
                [
                    'name' => 'Show Deactivated Users',
                    'icon' => 'fas fa-store-alt-slash',
                    'link' => '#',
                ],
            ];
        }

        $roleBuild = [];
        if($this->getPermissionValue('Manage Permissions'))
        {
            $roleBuild = [[
                'name' => 'User Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'link' => '#',
            ]];
        }

        return ['users' => array_merge($userBuild, $roleBuild)];
    }


    //  Determine if the user has permissions for a specific area
    protected function getPermissionValue($description)
    {
        $allowed = UserRolePermissions::where('role_id', Auth::user()->role_id)->whereHas('UserRolePermissionTypes', function($q) use ($description)
        {
            $q->where('description', $description);
        })->first();

        return $allowed->allow;
    }
}
