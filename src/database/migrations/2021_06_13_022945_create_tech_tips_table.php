<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechTipsTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('tech_tips', function (Blueprint $table) {
            $table->id('tip_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('tip_type_id');
            $table->boolean('sticky');
            $table->text('subject');
            $table->text('slug');
            $table->longText('details');
            $table->integer('views')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade');
            $table->foreign('updated_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade');
            $table->foreign('tip_type_id')
                ->references('tip_type_id')
                ->on('tech_tip_types')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('tech_tips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['updated_id']);
            $table->dropForeign(['tip_type_id']);
        });
        Schema::dropIfExists('tech_tips');
    }
}
