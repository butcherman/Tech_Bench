<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechTipFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_tip_files', function (Blueprint $table) {
            $table->increments('tip_file_id');
            $table->integer('tip_id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->timestamps();
            $table->foreign('tip_id')->references('tip_id')->on('tech_tips')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('file_id')->references('file_id')->on('files')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tech_tip_files');
    }
}
