<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contact_phones', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cont_id')->unsigned();
            $table->integer('phone_type_id')->unsigned();
            $table->text('phone_number');
            $table->text('extension')->nullable();
            $table->timestamps();
            $table->foreign('cont_id')->references('cont_id')->on('customer_contacts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('phone_type_id')->references('phone_type_id')->on('phone_number_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_contact_phones', function(Blueprint $table)
        {
            $table->dropForeign(['cont_id', 'phone_type_id']);
        });
        Schema::dropIfExists('customer_contact_phones');
    }
}
