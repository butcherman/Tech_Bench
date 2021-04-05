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
    /**
     *  Administration Home Page
     */
    public function __invoke()
    {
        Gate::authorize('admin-link', Auth::user());

        //  Build each of the Administration menus depending on customer's access
        $userBuild      = $this->buildUserList();
        $equipmentBuild = $this->buildEquipmentList();





        return Inertia::render('Admin/index', [
            'links' => array_merge($userBuild, $equipmentBuild),
        ]);
    }

    //  Build the user administration links if the user has access
    protected function buildUserList()
    {
        $userBuild = [];
        if($this->getPermissionValue('Manage Users'))
        {
            $userBuild = [
                [
                    'name' => 'Create New User',
                    'icon' => 'fas fa-user-plus',
                    'link' => route('admin.user.create'),
                ],
                [
                    'name' => 'Modify User',
                    'icon' => 'fas fa-user-edit',
                    'link' => route('admin.user.list'),
                ],
                [
                    'name' => 'Show Deactivated Users',
                    'icon' => 'fas fa-store-alt-slash',
                    'link' => route('admin.disabled.index'),
                ],
            ];
        }

        $roleBuild = [];
        if($this->getPermissionValue('Manage Permissions'))
        {
            $roleBuild = [[
                'name' => 'User Roles and Permissions',
                'icon' => 'fas fa-users-cog',
                'link' => route('admin.user-roles.index'),
            ]];
        }

        return ['users' => array_merge($userBuild, $roleBuild)];
    }

    //  Build the equipment administration links if the user has access
    protected function buildEquipmentList()
    {
        if($this->getPermissionValue('Manage Equipment'))
        {
            return [
                'equipment types and categories' => [
                    [
                        'name' => 'Create New Category',
                        'icon' => 'far fa-plus-square',
                        'link' => route('admin.equipment.categories.create'),
                    ],
                    [
                        'name' => 'Modify Existing Category',
                        'icon' => 'far fa-edit',
                        'link' => route('admin.equipment.categories.index'),
                    ],
                    [
                        'name' => 'Create New Equipment',
                        'icon' => 'far fa-plus-square',
                        'link' => route('admin.equipment.create'),
                    ],
                    [
                        'name' => 'Modify Existing Equipment',
                        'icon' => '',
                        'link' => '#',
                    ],
                    [
                        'name' => 'Modify Information Gathered for Customer Equipment',
                        'icon' => '',
                        'link' => '#',
                    ],
                ]
                ];
        }

        return [];
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
