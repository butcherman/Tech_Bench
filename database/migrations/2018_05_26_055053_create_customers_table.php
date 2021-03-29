<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function(Blueprint $table) {
            $table->increments('cust_id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->text('name');
            $table->text('dba_name')->nullable();
            $table->text('address');
            $table->text('city');
            $table->text('state');
            $table->integer('zip');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('parent_id')->references('cust_id')->on('customers')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
