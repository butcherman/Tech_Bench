<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLinkInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_link_instructions', function (Blueprint $table) {
            $table->increments('link_instructions_id');
            $table->integer('link_id')->unsigned();
            $table->longText('instruction')->nullable();
            $table->timestamps();
            $table->foreign('link_id')->references('link_id')->on('file_links')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_link_instructions');
    }
}
