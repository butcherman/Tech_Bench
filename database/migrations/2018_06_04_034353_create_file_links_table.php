<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_links', function(Blueprint $table) {
            $table->increments('link_id');
            $table->integer('user_id')->unsigned();
            $table->integer('cust_id')->unsigned()->nullable();
            $table->text('link_hash');
            $table->text('link_name');
            $table->date('expire');
            $table->boolean('allow_upload');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_links', function(Blueprint $table)
        {
            $table->dropForeign(['user_id', 'cust_id']);
        });
        Schema::dropIfExists('file_links');
    }
}
