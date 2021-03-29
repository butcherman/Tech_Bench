<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_types', function(Blueprint $table) {
            $table->increments('sys_id');
            $table->integer('cat_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->foreign('cat_id')->references('cat_id')->on('equipment_categories')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_types', function(Blueprint $table)
        {
            $table->dropForeign(['cat_id']);
        });

        Schema::dropIfExists('equipment_types');
    }
}
