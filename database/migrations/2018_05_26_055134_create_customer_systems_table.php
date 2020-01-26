<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_systems', function(Blueprint $table) {
            $table->increments('cust_sys_id');
            $table->integer('cust_id')->unsigned();
            $table->integer('sys_id')->unsigned();
            $table->boolean('shared')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sys_id')->references('sys_id')->on('system_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_systems');
    }
}
