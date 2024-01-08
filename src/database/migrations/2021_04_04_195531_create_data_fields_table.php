<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_fields', function (Blueprint $table) {
            $table->id('field_id');
            $table->unsignedBigInteger('equip_id');
            $table->unsignedBigInteger('type_id');
            $table->integer('order');
            $table->timestamps();
            $table->foreign('equip_id')->references('equip_id')->on('equipment_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('type_id')->on('data_field_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_fields', function (Blueprint $table) {
            $table->dropForeign(['equip_id', 'type_id']);
        });
        Schema::dropIfExists('data_fields');
    }
}
