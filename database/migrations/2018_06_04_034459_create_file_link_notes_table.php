<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLinkNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_link_notes', function(Blueprint $table) {
            $table->increments('link_note_id');
            $table->integer('link_id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->longText('note');
            $table->timestamps();
            $table->foreign('link_id')->references('link_id')->on('file_links')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('file_id')->references('file_id')->on('files')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_link_notes');
    }
}
