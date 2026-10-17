<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id('setting_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('setting_type_id');
            $table->boolean('value')->default(1);
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('setting_type_id')
                ->references('setting_type_id')
                ->on('user_setting_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
