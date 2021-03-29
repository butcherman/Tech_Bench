<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechTipEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_tip_equipment', function(Blueprint $table) {
            $table->increments('tip_tag_id');
            $table->integer('tip_id')->unsigned();
            $table->integer('sys_id')->unsigned();
            $table->timestamps();
            $table->foreign('tip_id')->references('tip_id')->on('tech_tips')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sys_id')->references('sys_id')->on('equipment_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tech_tip_equipment', function(Blueprint $table)
        {
            $table->dropForeign(['tip_id', 'sys_id']);
        });
        Schema::dropIfExists('tech_tip_equipment');
    }
}
