<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
