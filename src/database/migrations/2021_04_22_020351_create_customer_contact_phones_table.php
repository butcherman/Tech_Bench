<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContactPhonesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('customer_contact_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cont_id');
            $table->unsignedBigInteger('phone_type_id');
            $table->text('phone_number');
            $table->text('extension')->nullable();
            $table->timestamps();
            $table->foreign('cont_id')
                ->references('cont_id')
                ->on('customer_contacts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('phone_type_id')
                ->references('phone_type_id')
                ->on('phone_number_types')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::dropIfExists('customer_contact_phones');
    }
}
