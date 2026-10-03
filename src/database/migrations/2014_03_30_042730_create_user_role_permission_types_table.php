<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePermissionTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('user_role_permission_types', function (Blueprint $table) {
            $table->id('perm_type_id');
            $table->text('description');
            $table->boolean('is_admin_link')->default(0);
            $table->timestamps();
        });
    }
}
