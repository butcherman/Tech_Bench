<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade');
            $table->foreign('updated_id')->references('user_id')->on('users')->onUpdate('cascade');
            $table->foreign('tip_type_id')->references('tip_type_id')->on('tech_tip_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tech_tips', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['updated_id']);
            $table->dropForeign(['tip_type_id']);
        });
        Schema::dropIfExists('tech_tips');
    }
}
