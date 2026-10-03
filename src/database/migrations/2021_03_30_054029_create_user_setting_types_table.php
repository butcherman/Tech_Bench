<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('user_setting_types', function (Blueprint $table) {
            $table->id('setting_type_id');
            $table->text('name');
            $table->unsignedBigInteger('perm_type_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::dropIfExists('user_setting_types');
    }
}
