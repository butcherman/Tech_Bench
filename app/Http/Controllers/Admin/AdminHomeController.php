<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Nwidart\Modules\Facades\Module;

use App\Models\UserRolePermissions;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $custBuild      = $this->buildCustomerList();
        $moduleBuild    = $this->buildModuleAdmin();



        return Inertia::render('Admin/index', [
            'links' => array_merge($userBuild, $equipmentBuild, $custBuild, $moduleBuild),
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
        $nav = [];
        if($this->getPermissionValue('Manage Equipment'))
        {
            $nav = [
                'equipment types and categories' => [
                    [
                        'name' => 'Create New Category',
                        'icon' => 'fas fa-plus-square',
                        'link' => route('admin.equipment.categories.create'),
                    ],
                    [
                        'name' => 'Modify Existing Category',
                        'icon' => 'fas fa-edit',
                        'link' => route('admin.equipment.categories.index'),
                    ],
                    [
                        'name' => 'Create New Equipment',
                        'icon' => 'fas fa-plus-square',
                        'link' => route('admin.equipment.create'),
                    ],
                    [
                        'name' => 'Modify Existing Equipment',
                        'icon' => 'fas fa-edit',
                        'link' => route('admin.equipment.index'),
                    ],
                    //  TODO - Finish Me
                    // [
                    //     'name' => 'Modify Information Gathered for Customer Equipment',
                    //     'icon' => '',
                    //     'link' => '#',
                    // ],
                ]
                ];
        }

        return $nav;
    }

    //  Build Customer Management list
    protected function buildCustomerList()
    {
        $nav = [];

        if($this->getPermissionValue('Manage Customers'))
        {
            $nav = [
                'Manage Customers' => [
                    [
                        'name' => 'Change Customer ID Number',
                        'icon' => 'fas fa-fingerprint',
                        'link' => route('customers.change-id.index'),
                    ],
                    [
                        'name' => 'View Deactivated Customers',
                        'icon' => 'fas fa-ban',
                        'link' => route('customers.show-deactivated'),
                    ],
                    [
                        'name' => 'View Deleted Customer Information',
                        'icon' => 'fas fa-trash-alt',
                        'link' => '#',
                    ],
                ],
            ];
        }

        return $nav;
    }

    protected function buildModuleAdmin()
    {
        $nav = [];
        $modules = Module::allEnabled();

        foreach($modules as $module)
        {
            $name    = $module->getLowerName();
            $navData = config($name.'.admin_nav');
            $modNav  = [];

            foreach($navData as $n)
            {
                $modNav[] = [
                    'name' => $n['name'],
                    'link' => route($n['route']),
                    'icon' => $n['icon'],
                ];
            }

            //  Split Camel Case name into normal name
            $nav[implode(' ',preg_split('/(?=[A-Z])/', $module->getName()))] = $modNav;
        }

        return $nav;
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
