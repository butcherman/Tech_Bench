<?php

use App\UserRolePermissionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesForVersion53 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->updateUserRolePermissionTypes();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserRolePermissionTypes::where('description', 'Manage Permissions')->update(['description' => 'Manage User Roles']);
    }

    protected function updateUserRolePermissionTypes()
    {
        //  Update the default data for the user_role_permission_types table
        UserRolePermissionTypes::where('description', 'Manage User Roles')->update([
            'description' => 'Manage Permissions',
        ]);
    }
}
