<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePermissionCategoriesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('user_role_permission_categories', function (Blueprint $table) {
            $table->id('role_cat_id');
            $table->text('category');
            $table->timestamps();
        });

        //  Add the category column to user role permission types
        Schema::table('user_role_permission_types', function (Blueprint $table) {
            $table->unsignedBigInteger('role_cat_id')
                ->after('perm_type_id')->nullable();
            $table->foreign('role_cat_id')
                ->references('role_cat_id')
                ->on('user_role_permission_categories')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('user_role_permission_types', function (Blueprint $table) {
            $table->dropForeign(['role_cat_id']);
            $table->dropColumn('role_cat_id');
        });

        Schema::dropIfExists('user_role_permission_categories');
    }
}
