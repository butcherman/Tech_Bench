<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSystemFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_system_fields', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cust_sys_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->text('value')->nullable();
            $table->timestamps();
            $table->foreign('cust_sys_id')->references('cust_sys_id')->on('customer_systems')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('field_id')->references('field_id')->on('system_cust_data_fields')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_system_fields');
    }
}
