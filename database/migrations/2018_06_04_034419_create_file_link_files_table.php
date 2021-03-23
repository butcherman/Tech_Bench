<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLinkFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_link_files', function(Blueprint $table) {
            $table->increments('link_file_id');
            $table->integer('link_id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('added_by')->nullable();
            $table->boolean('upload')->default(1);
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->foreign('link_id')->references('link_id')->on('file_links')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('file_id')->references('file_id')->on('files')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_link_files');
    }
}
