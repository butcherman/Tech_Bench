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
        Schema::create('tech_tips', function(Blueprint $table) {
            $table->increments('tip_id');
            $table->integer('user_id')->unsigned();
            $table->integer('updated_id')->unsigned()->nullable();
            $table->boolean('public')->default(0);
            $table->boolean('sticky')->default(0);
            $table->bigInteger('tip_type_id')->unsigned();
            $table->text('subject');
            $table->longText('description');
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
            $table->dropForeign(['user_id', 'updated_id', 'tip_type_id']);
        });
        Schema::dropIfExists('tech_tips');
    }
}
