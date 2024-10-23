<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTechTipBookmarksTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('user_tech_tip_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tip_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->unique(['user_id', 'tip_id']);
            $table->foreign('tip_id')
                ->references('tip_id')
                ->on('tech_tips')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('user_tech_tip_bookmarks', function (Blueprint $table) {
            $table->dropForeign(['tip_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('user_tech_tip_bookmarks');
    }
}
