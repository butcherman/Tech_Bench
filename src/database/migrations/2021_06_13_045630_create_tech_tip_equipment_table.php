<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechTipEquipmentTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('tech_tip_equipment', function (Blueprint $table) {
            $table->id('tip_equip_id');
            $table->unsignedBigInteger('tip_id');
            $table->unsignedBigInteger('equip_id');
            $table->timestamps();
            $table->foreign('tip_id')
                ->references('tip_id')
                ->on('tech_tips')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('equip_id')
                ->references('equip_id')
                ->on('equipment_types')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('tech_tip_equipment', function (Blueprint $table) {
            $table->dropForeign(['tip_id']);
            $table->dropForeign(['equip_id']);
        });
        Schema::dropIfExists('tech_tip_equipment');
    }
}
