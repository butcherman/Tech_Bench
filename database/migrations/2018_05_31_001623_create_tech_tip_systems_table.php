<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechTipSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_tip_systems', function (Blueprint $table) {
            $table->increments('tip_tag_id');
            $table->integer('tip_id')->unsigned();
            $table->integer('sys_id')->unsigned();
            $table->timestamps();
            $table->foreign('tip_id')->references('tip_id')->on('tech_tips')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sys_id')->references('sys_id')->on('system_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tech_tip_systems');
    }
}
