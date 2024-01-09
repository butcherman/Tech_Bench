<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTechTipRecentsTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('user_tech_tip_recents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tip_id');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('tip_id')
                ->references('tip_id')
                ->on('tech_tips')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('user_tech_tip_recents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['tip_id']);
        });
        Schema::dropIfExists('user_tech_tip_recents');
    }
}
