<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_files', function (Blueprint $table) {
            $table->increments('sys_file_id');
            $table->integer('sys_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('sys_id')->references('sys_id')->on('system_types')->onUpdate('cascade');
            $table->foreign('type_id')->references('type_id')->on('system_file_types')->onUpdate('cascade');
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
        Schema::dropIfExists('system_files');
    }
}
