<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateUserRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('perm_type_id')->unsigned();
            $table->boolean('allow')->default(0);
            $table->timestamps();
            $table->foreign('role_id')->references('role_id')->on('user_role_types')->onUpdate('cascade');
            $table->foreign('perm_type_id')->references('perm_type_id')->on('user_role_permission_types')->onUpdate('cascade');
        });

        $defaults = [
            1 => [
                1  => 1,
                2  => 1,
                3  => 1,
                4  => 1,
                5  => 1,
                6  => 1,
                7  => 1,
                8  => 1,
                9  => 1,
                10 => 1,
                11 => 1,
            ],
            2 => [
                1  => 1,
                2  => 1,
                3  => 1,
                4  => 1,
                5  => 1,
                6  => 1,
                7  => 1,
                8  => 1,
                9  => 1,
                10 => 1,
                11 => 1,
            ],
            3 => [
                1  => 0,
                2  => 0,
                3  => 1,
                4  => 1,
                5  => 0,
                6  => 0,
                7  => 1,
                8  => 1,
                9  => 0,
                10 => 0,
                11 => 0,
            ],
            4 => [
                1  => 0,
                2  => 0,
                3  => 0,
                4  => 1,
                5  => 0,
                6  => 0,
                7  => 1,
                8  => 1,
                9  => 0,
                10 => 0,
                11 => 0,
            ],
        ];

        foreach($defaults as $role_id => $perms)
        {
            foreach($perms as $perm_type_id => $allow)
            {
                DB::table('user_role_permissions')->insert([
                    'role_id'      => $role_id,
                    'perm_type_id' => $perm_type_id,
                    'allow'        => $allow,
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role_permissions');
    }
}
