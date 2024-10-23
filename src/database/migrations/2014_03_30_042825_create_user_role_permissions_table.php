<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePermissionsTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('user_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('perm_type_id');
            $table->boolean('allow')->default(0);
            $table->timestamps();
            $table->foreign('role_id')
                ->references('role_id')
                ->on('user_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('perm_type_id')
                ->references('perm_type_id')
                ->on('user_role_permission_types')
                ->onUpdate('cascade');
        });

        /**
         * Setup the default role permissions
         */
        $defaults = [
            1 => [
                1 => 1,
                2 => 1,
                3 => 1,
                4 => 1,
                5 => 1,
                6 => 1,
                7 => 1,
                8 => 1,
                9 => 1,
                10 => 1,
                11 => 1,
                12 => 1,
                13 => 1,
                14 => 1,
                15 => 1,
                16 => 1,
                17 => 1,
                18 => 1,
                19 => 1,
                20 => 1,
                21 => 1,
                22 => 1,
                23 => 1,
                24 => 1,
                25 => 1,
                26 => 1,
                27 => 1,
            ],
            2 => [
                1 => 0,
                2 => 1,
                3 => 1,
                4 => 1,
                5 => 1,
                6 => 1,
                7 => 1,
                8 => 1,
                9 => 1,
                10 => 1,
                11 => 1,
                12 => 1,
                13 => 1,
                14 => 1,
                15 => 1,
                16 => 1,
                17 => 1,
                18 => 1,
                19 => 1,
                20 => 1,
                21 => 1,
                22 => 1,
                23 => 1,
                24 => 1,
                25 => 1,
                26 => 1,
                27 => 1,
            ],
            3 => [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 1,
                5 => 0,
                6 => 0,
                7 => 1,
                8 => 1,
                9 => 0,
                10 => 0,
                11 => 1,
                12 => 1,
                13 => 0,
                14 => 1,
                15 => 1,
                16 => 1,
                17 => 1,
                18 => 1,
                19 => 1,
                20 => 1,
                21 => 1,
                22 => 1,
                23 => 1,
                24 => 0,
                25 => 0,
                26 => 0,
                27 => 1,
            ],
            4 => [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 1,
                8 => 1,
                9 => 0,
                10 => 0,
                11 => 1,
                12 => 1,
                13 => 0,
                14 => 1,
                15 => 1,
                16 => 1,
                17 => 1,
                18 => 1,
                19 => 1,
                20 => 1,
                21 => 1,
                22 => 1,
                23 => 1,
                24 => 0,
                25 => 0,
                26 => 0,
                27 => 1,
            ],
        ];

        foreach ($defaults as $role_id => $perms) {
            foreach ($perms as $perm_type_id => $allow) {
                DB::table('user_role_permissions')->insert([
                    'role_id' => $role_id,
                    'perm_type_id' => $perm_type_id,
                    'allow' => $allow,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('user_role_permissions', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['perm_type_id']);
        });

        Schema::dropIfExists('user_role_permissions');
    }
}
