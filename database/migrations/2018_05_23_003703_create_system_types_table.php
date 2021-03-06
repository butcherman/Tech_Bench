<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_types', function(Blueprint $table) {
            $table->increments('sys_id');
            $table->integer('cat_id')->unsigned();
            $table->string('name');
            $table->string('folder_location');
            $table->timestamps();
            $table->foreign('cat_id')->references('cat_id')->on('system_categories')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_types');
    }
}
