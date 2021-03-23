<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemDataFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_data_fields', function(Blueprint $table) {
            $table->increments('field_id');
            $table->integer('sys_id')->unsigned();
            $table->integer('data_type_id')->unsigned();
            $table->integer('order');
            $table->timestamps();
            $table->foreign('sys_id')->references('sys_id')->on('system_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('data_type_id')->references('data_type_id')->on('system_data_field_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_data_fields');
    }
}
