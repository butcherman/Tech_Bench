<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contacts', function(Blueprint $table) {
            $table->increments('cont_id');
            $table->integer('cust_id')->unsigned();
            $table->boolean('shared')->default(0);
            $table->text('name');
            $table->text('email')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('customer_contacts');
    }
}
