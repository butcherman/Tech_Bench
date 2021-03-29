<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_files', function(Blueprint $table) {
            $table->increments('cust_file_id');
            $table->integer('file_id')->unsigned();
            $table->integer('file_type_id')->unsigned();
            $table->integer('cust_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('shared')->default(0);
            $table->text('name');
            $table->timestamps();
            $table->foreign('file_id')->references('file_id')->on('files')->onUpdate('cascade');
            $table->foreign('file_type_id')->references('file_type_id')->on('customer_file_types')->onUpdate('cascade');
            $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('customer_files', function(Blueprint $table)
        {
            $table->dropForeign(['file_id', 'file_type_id', 'cust_id', 'user_id']);
        });
        Schema::dropIfExists('customer_files');
    }
}
