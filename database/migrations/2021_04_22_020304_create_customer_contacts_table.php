<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id('cont_id');
            $table->unsignedBigInteger('cust_id');
            $table->boolean('shared');
            $table->text('name');
            $table->text('email')->nullable();
            $table->text('title')->nullable();
            $table->longText('note')->nullable();
            $table->softDeletes();
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
        Schema::table('customer_contacts', function (Blueprint $table) {
            $table->dropForeign(['cust_id']);
        });
        Schema::dropIfExists('customer_contacts');
    }
}
